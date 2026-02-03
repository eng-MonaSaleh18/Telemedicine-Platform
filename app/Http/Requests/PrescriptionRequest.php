<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
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
            'consultation_id' => 'required|exists:consultations,id',
            'medication' => 'required|string',
            'instructions' => 'required|string',
            'signature' => 'required|string',
            'chronic_diseases' => 'required|string',
            'allergies' => 'required|string',
            'current_medications' => 'required|string',
            'medical_files' => 'required|array',
            'medical_files.*.file_path' => 'file|mimes:jpg,png,pdf|max:2048', // Max 2MB
            'medical_files.*.file_name' => 'required|string',
            'medical_files.*.description' => 'required|string',
        ];
    }
}
