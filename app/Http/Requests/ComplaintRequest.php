<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
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
            'doctor_id' => 'required|exists:doctors,id',
            'complaint_type' => 'required|in:unprofessional_behavior,delayed_response,wrong_diagnosis,billing_issue,technical_issue,other',
            'description' => 'required|string|min:20|max:1000',
            'contact_number' => 'required|string|regex:/^[0-9\+\-\(\) ]{7,}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'The target user is required.',
            'doctor_id.exists' => 'The selected user does not exist.',
            'complaint_type.required' => 'The complaint type is required.',
            'complaint_type.in' => 'Invalid complaint type selected.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least 20 characters.',
            'description.max' => 'Description may not exceed 1000 characters.',
            'contact_number.required' => 'Contact number is required.',
            'contact_number.regex' => 'Contact number format is invalid.',
        ];
    }
}
