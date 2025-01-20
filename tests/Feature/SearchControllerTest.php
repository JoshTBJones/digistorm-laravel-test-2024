<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_search_page()
    {
        $response = $this->get(route('search.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.search.index');
    }

    /** @test */
    public function it_returns_contacts_based_on_search_query()
    {
        $contact1 = Contact::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $contact2 = Contact::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);
        $contact3 = Contact::factory()->create(['first_name' => 'Alice', 'last_name' => 'Johnson']);

        $response = $this->get(route('search.index', ['query' => 'John']));

        $response->assertStatus(200);
        $response->assertViewIs('pages.search.index');
        $response->assertViewHas('results', function ($results) use ($contact1, $contact3) {
            return $results->pluck('id')->sort()->values()->toArray() === [
                $contact1->id, 
                $contact3->id
            ];
        });
    }

    /** @test */
    public function it_handles_partial_and_special_character_queries()
    {
        $contact = Contact::factory()->create(['first_name' => 'John', 'last_name' => 'O\'Connor']);

        $response = $this->get(route('search.index', ['query' => "O'Con"]));

        $response->assertStatus(200)
                 ->assertViewIs('pages.search.index')
                 ->assertViewHas('results', function ($results) use ($contact) {
                     return $results->pluck('id')->contains($contact->id);
                 });
    }

    /** @test */
    public function it_safely_handles_malicious_search_queries()
    {
        // Create a test contact
        $contact = Contact::factory()->create([
            'first_name' => 'Normal',
            'last_name' => 'User',
            'company_name' => 'Test Corp'
        ]);

        // Test SQL injection attempts
        $maliciousQueries = [
            "' OR '1'='1",
            "; DROP TABLE contacts;",
            "' UNION SELECT * FROM users --",
            "<script>alert('xss')</script>",
            "%' OR first_name LIKE '%"
        ];

        foreach ($maliciousQueries as $query) {
            $route = route('search.index', ['query' => $query]);

            $response = $this->get($route);

            $response->assertStatus(200);
            $response->assertViewIs('pages.search.index');
            // Verify the application continues to function normally
            $response->assertDontSee('SQL syntax');
            $response->assertDontSee('error');

            // Verify no JavaScript execution
            $response->assertDontSee('<script>alert("xss")</script>', false);
        }

        // Verify the contacts table still exists and contains our test data
        $this->assertDatabaseHas('contacts', [
            'first_name' => 'Normal',
            'last_name' => 'User'
        ]);
    }

    /** @test */
    public function it_handles_empty_search_query()
    {
        $response = $this->get(route('search.index', ['query' => '']));

        $response->assertStatus(200);
        $response->assertViewIs('pages.search.index');
        $response->assertViewHas('results', function ($results) {
            return empty($results) || $results->isEmpty();
        });
    }
}