<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'teacher';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'reference_files.*' => ['nullable', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,doc,docx', 'max:10240'], // 10MB limit per file
        ];
    }
    
    public function messages(): array
    {
        return [
            'reference_files.*.mimes' => 'The file must be a file of type: png, jpeg, pdf, excel, docx.',
        ];
    }
}
