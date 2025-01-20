<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\PhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_phone_numbers()
    {
        $contact = Contact::factory()->create();
        $phoneNumber = PhoneNumber::factory()->create(['contact_id' => $contact->id]);

        $this->assertTrue($contact->phoneNumbers->contains($phoneNumber));
    }

    /** @test */
    public function it_can_get_full_name_attribute()
    {
        $contact = Contact::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $contact->full_name);
    }

    /** @test */
    public function it_deletes_related_phone_numbers_when_deleted()
    {
        $contact = Contact::factory()->create();
        $phoneNumber = PhoneNumber::factory()->create(['contact_id' => $contact->id]);

        $contact->delete();

        $this->assertDatabaseMissing('phone_numbers', ['id' => $phoneNumber->id]);
    }

    /** @test */
    public function it_can_create_a_contact()
    {
        $contactData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'DOB' => '1990-01-01',
            'company_name' => 'ACME Inc',
            'position' => 'Manager'
        ];

        $contact = Contact::create($contactData);

        $this->assertDatabaseHas('contacts', $contactData);
        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('John', $contact->first_name);
    }

    /** @test */
    public function it_can_read_a_contact()
    {
        $contact = Contact::factory()->create([
            'first_name' => 'Jane',
            'email' => 'jane@example.com'
        ]);

        $foundContact = Contact::find($contact->id);

        $this->assertInstanceOf(Contact::class, $foundContact);
        $this->assertEquals('Jane', $foundContact->first_name);
        $this->assertEquals('jane@example.com', $foundContact->email);
    }

    /** @test */
    public function it_can_update_a_contact()
    {
        $contact = Contact::factory()->create();

        $contact->update([
            'first_name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'first_name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    /** @test */
    public function it_can_delete_a_contact()
    {
        $contact = Contact::factory()->create();
        $contactId = $contact->id;

        $contact->delete();

        $this->assertDatabaseMissing('contacts', ['id' => $contactId]);
        $this->assertNull(Contact::find($contactId));
    }
}