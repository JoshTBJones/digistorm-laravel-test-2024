<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'DOB',
        'company_name',
        'position',
        'email',
        'phone_number'
    ];

    /**
     * Phone numbers that belong to this contact.
     */
    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }

    /**
     * Get contact's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Synchronize phone numbers for this contact.
     *
     * This method takes an array of phone numbers and syncs them with the contact,
     * removing any numbers not in the provided array and adding new ones.
     * Empty values are filtered out before syncing.
     *
     * @param array $numbers Array of phone numbers to sync
     * @return void
     */
    public function syncPhoneNumbers(array $numbers): void
    {
        // Remove empty values
        $numbers = array_filter($numbers);

        // Get existing phone numbers
        $existingNumbers = $this->phoneNumbers()->pluck('number')->toArray();

        // Find numbers to delete
        $numbersToDelete = array_diff($existingNumbers, $numbers);

        // Find numbers to add
        $numbersToAdd = array_diff($numbers, $existingNumbers);

        // Delete numbers not in the provided array
        $this->phoneNumbers()->whereIn('number', $numbersToDelete)->delete();

        // Add new numbers
        foreach ($numbersToAdd as $number) {
            $this->phoneNumbers()->create(['number' => $number]);
        }
    }


    /**
     * Clean-up dependent records.
     */
    public static function boot() {
        parent::boot();
        self::deleting(function($contact) {
             $contact->phoneNumbers()->each(function($phoneNumber) {
                $phoneNumber->delete();
             });
        });
    }
}
