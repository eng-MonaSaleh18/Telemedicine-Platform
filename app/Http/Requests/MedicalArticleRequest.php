<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalArticleRequest extends FormRequest
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
            'title' => 'required|string|min:10|max:255|unique:medical_articles,title',
            'content' => 'required|string|min:100',
        ];
    }
    public function messages(): array
    {
        return [
            // Title
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.min' => 'The title must be at least 10 characters.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'title.unique' => 'The title has already been taken.',

            // Content
            'content.required' => 'The content field is required.',
            'content.string' => 'The content must be a string.',
            'content.min' => 'The content must be at least 100 characters.',
        ];
    }
}
