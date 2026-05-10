<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'student';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'submission_file' => ['required', 'file', 'mimes:pdf,xls,xlsx,docx,json', 'max:25600'],
        ];
    }

    public function messages(): array
    {
        return [
            'submission_file.required' => 'Please upload a file before submitting.',
            'submission_file.mimes' => 'Allowed file types are PDF, Excel, DOCX, and JSON.',
            'submission_file.max' => 'The file size must not exceed 25 MB.',
        ];
    }
}
