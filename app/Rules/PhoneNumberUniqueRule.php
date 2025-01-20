<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class PhoneNumberUniqueRule implements ValidationRule
{
    protected $contactId;
    protected $requestNumbers;

    public function __construct($contactId, $requestNumbers)
    {
        $this->contactId = $contactId;
        $this->requestNumbers = $requestNumbers;
    }

    /**
     * Validate that the phone number is unique for contacts except the current one.
     *
     * @param string $attribute The name of the attribute being validated
     * @param mixed $value The value of the attribute being validated
     * @param \Closure $fail The callback to invoke on validation failure
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the number already exists in the database but is not linked to this contact
        $numberExists = DB::table('phone_numbers')
            ->where('number', $value)
            ->where('contact_id', '!=', $this->contactId)
            ->exists();

        if ($numberExists) {
            $fail('The ' . $attribute . ' has already been taken.');
            return;
        }

        // Check for duplicates in the request payload
        $attributeIndex = explode('.', $attribute)[1] ?? null;

        if (is_numeric($attributeIndex)) {
            $duplicates = array_keys(array_filter($this->requestNumbers, function ($number) use ($value) {
                return $number === $value;
            }));

            if (count($duplicates) > 1) {
                $fail('The ' . $attribute . ' is duplicated in the request.');
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute has already been taken or duplicated.';
    }
}
