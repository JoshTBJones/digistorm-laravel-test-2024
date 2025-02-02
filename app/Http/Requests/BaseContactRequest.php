<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'number'       => 'required|array',
        ];
    }
}
