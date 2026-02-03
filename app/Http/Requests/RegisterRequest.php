<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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

            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',

            'role' => ['required', Rule::in(['patient', 'doctor'])],

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer',
            

            'doctorCredential' => 'array',
            'doctorCredential.*.file_path' => 'required_if:role,doctor|file|mimes:pdf,jpg,png|max:2048',
            'doctorCredential.*.file_name' => 'required_if:role,doctor|max:255|string',
            'doctorCredential.*.description' => 'required_if:role,doctor|max:255|string',
            'specialization_id' => 'required_if:role,doctor' ,

            'fcm_token' => 'nullable|string'

        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.string' => 'The email must be a string.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'The email has already been taken.',

            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',

            'role.required' => 'The role field is required.',
            'role.in' => 'The role must be either "patient" or "doctor".',

            'first_name.required' => 'The first name field is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name may not be greater than 255 characters.',

            'last_name.required' => 'The last name field is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',

            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',

            'phone.required' => 'The phone number field is required.',
            'phone.string' => 'The phone number must be a string.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',

            'age.required' => 'The age field is required.',
            'age.integer' => 'The age must be an integer.',

            'doctorCredential.array' => 'The doctor credentials must be an array.',

            'doctorCredential.*.file_path.required_if' => 'The credential file is required when the role is doctor.',
            'doctorCredential.*.file_path.file' => 'The credential must be a valid file.',
            'doctorCredential.*.file_path.mimes' => 'The file must be of type pdf, jpg, or png.',
            'doctorCredential.*.file_path.max' => 'The file size may not exceed 2 megabytes.',

            'doctorCredential.*.file_name.required_if' => 'The file name is required when the role is doctor.',
            'doctorCredential.*.file_name.max' => 'The file name may not be greater than 255 characters.',
            'doctorCredential.*.file_name.string' => 'The file name must be a string.',

            'doctorCredential.*.description.required_if' => 'The description is required when the role is doctor.',
            'doctorCredential.*.description.max' => 'The description may not be greater than 255 characters.',
            'doctorCredential.*.description.string' => 'The description must be a string.',
        ];
    }
}
