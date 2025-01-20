<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\PhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_contacts_index_page()
    {
        $response = $this->get(route('contacts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.contacts.index');
    }

    /** @test */
    public function it_displays_create_contact_form()
    {
        $response = $this->get(route('contacts.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.contacts.create');
    }

    /** @test */
    public function it_stores_a_new_contact()
    {
        $contactData = Contact::factory()->make()->toArray();
        $contactData['number'] = ['+1234567890'];

        $response = $this->post(route('contacts.store'), $contactData);

        $createdContact = Contact::where('first_name', $contactData['first_name'])->first();

        $response->assertRedirect(route('contacts.show', $createdContact->id));
        $this->assertDatabaseHas('contacts', ['first_name' => $contactData['first_name']]);
        $this->assertDatabaseHas('phone_numbers', ['number' => '+1234567890']);
    }

    /** @test */
    public function it_displays_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->get(route('contacts.show', $contact));

        $response->assertStatus(200);
        $response->assertViewIs('pages.contacts.show');
        $response->assertViewHas('contact', $contact);
    }

    /** @test */
    public function it_updates_a_contact()
    {
        $contact = Contact::factory()->create();

        $updateData = $contact->toArray();
        $updateData['first_name'] = 'Updated Name';
        $updateData['number'][] = '+447497538579';

        $response = $this->put(route('contacts.update', $contact), $updateData);

        $response->assertRedirect(route('contacts.show', $contact));
        $this->assertDatabaseHas('contacts', ['first_name' => 'Updated Name']);
        $this->assertDatabaseHas('phone_numbers', ['number' => '+447497538579']);
    }

    /** @test */
    public function it_deletes_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->delete(route('contacts.destroy', $contact));

        $response->assertRedirect(route('contacts.index'));
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}