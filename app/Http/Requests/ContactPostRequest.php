<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactPostRequest extends FormRequest
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
        return [
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'DOB'          => 'date|nullable',
            'company_name' => 'required|string|max:255',
            'position'     => 'required|string|max:255',
            'email'        => 'email|nullable|unique:contacts,email',
            'number.*'     => 'required|string|max:255|regex:/^\+?[1-9]\d{1,14}$/|unique:phone_numbers,number',
        ];
    }
}

