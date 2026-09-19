<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetNewPasswordRequest extends FormRequest
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
            'email'                     => 'required|email|exists:users,email',
            'new_password'              => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required'                  => 'Email address is required',
            'new_password.required'           => 'New password is required',
            'new_password.min'                => 'Password must be at least 8 characters',
            'new_password.confirmed'          => 'Passwords do not match',
            'new_password_confirmation.required' => 'Password confirmation is required',
        ];
    }
}
