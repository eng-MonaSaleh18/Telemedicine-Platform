<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ];
    }

    public function messages()
{
    return [
        'question.required' => 'The question field is mandatory.',
        'question.string' => 'The question must be text.',
        'question.max' => 'The question cannot exceed 255 characters.',
        
        'answer.required' => 'The answer field is mandatory.',
        'answer.string' => 'The answer must be text.',
    ];
}
}
