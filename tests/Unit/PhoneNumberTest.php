<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Contact;
use App\Models\PhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhoneNumberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_contact()
    {
        $contact = Contact::factory()->create();
        $phoneNumber = PhoneNumber::factory()->create(['contact_id' => $contact->id]);

        $this->assertInstanceOf(Contact::class, $phoneNumber->contact);
        $this->assertEquals($contact->id, $phoneNumber->contact->id);
    }

    /** @test */
    public function it_can_create_a_phone_number()
    {
        $contact = Contact::factory()->create();
        $phoneData = [
            'number' => '+1234567890',
            'contact_id' => $contact->id
        ];

        $phoneNumber = PhoneNumber::create($phoneData);

        $this->assertDatabaseHas('phone_numbers', $phoneData);
        $this->assertInstanceOf(PhoneNumber::class, $phoneNumber);
        $this->assertEquals('+1234567890', $phoneNumber->number);
    }

    /** @test */
    public function it_can_read_a_phone_number()
    {
        $contact = Contact::factory()->create();
        $phoneNumber = PhoneNumber::factory()->create([
            'number' => '+1234567890',
            'contact_id' => $contact->id
        ]);

        $foundPhoneNumber = PhoneNumber::find($phoneNumber->id);

        $this->assertInstanceOf(PhoneNumber::class, $foundPhoneNumber);
        $this->assertEquals('+1234567890', $foundPhoneNumber->number);
        $this->assertEquals($contact->id, $foundPhoneNumber->contact_id);
    }

    /** @test */
    public function it_can_update_a_phone_number()
    {
        $contact = Contact::factory()->create();
        $phoneNumber = PhoneNumber::factory()->create([
            'contact_id' => $contact->id
        ]);

        $phoneNumber->update([
            'number' => '+9876543210'
        ]);

        $this->assertDatabaseHas('phone_numbers', [
            'id' => $phoneNumber->id,
            'number' => '+9876543210'
        ]);
    }

    /** @test */
    public function it_can_delete_a_phone_number()
    {
        $contact = Contact::factory()->create();
        $phoneNumber = PhoneNumber::factory()->create([
            'contact_id' => $contact->id
        ]);
        $phoneNumberId = $phoneNumber->id;

        $phoneNumber->delete();

        $this->assertDatabaseMissing('phone_numbers', ['id' => $phoneNumberId]);
        $this->assertNull(PhoneNumber::find($phoneNumberId));
    }
}