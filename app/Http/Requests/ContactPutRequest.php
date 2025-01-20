<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseContactRequest;
use Illuminate\Validation\Rule;
use App\Rules\PhoneNumberUniqueRule;

class ContactPutRequest extends BaseContactRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'email' => [
                'email',
                'sometimes',
                'nullable',
                Rule::unique('contacts', 'email')->ignore($this->contact->id),
            ],
            'number.*' => [
                'string',
                'max:255',
                'regex:/^\+?[1-9]\d{9,14}$/',
                // Ensures phone numbers are unique for the given contact ID and within the provided numbers array
                new PhoneNumberUniqueRule($this->contact->id, $this->input('number', [])),
            ],
        ]);
    }
}
