<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'specialization_id' => 'required|integer',
            'languages' => 'required_if:role,doctor|max:255|string',
            'years_of_experience' => 'required_if:role,doctor|integer|min:0|max:50',

            'doctorCredential' => 'array',
            'doctorCredential.*.file_path' => 'required_if:role,doctor|file|mimes:pdf,jpg,png|max:2048',
            'doctorCredential.*.file_name' => 'required_if:role,doctor|max:255|string',
            'doctorCredential.*.description' => 'required_if:role,doctor|max:255|string',
        ];
    }
}
