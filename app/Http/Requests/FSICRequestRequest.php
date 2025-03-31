<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FSICRequestRequest extends FormRequest
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
            'application_id' => 'required|exists:applications,id',
            'amount' => 'required|numeric|min:500',
            'or_number' => 'required|string|max:10|unique:fsics,or_number',
            'payment_date' => 'required|date',
        ];
    }
}
