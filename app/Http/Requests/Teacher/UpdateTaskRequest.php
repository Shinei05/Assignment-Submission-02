<?php

namespace App\Http\Requests\Teacher;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'due_time' => [
                'required',
                'date_format:H:i',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (!$this->filled('due_date') || !$value) {
                        return;
                    }

                    $deadline = Carbon::parse($this->input('due_date') . ' ' . $value);

                    if ($deadline->isToday() && $deadline->lt(now())) {
                        $fail('Submission time cannot be earlier than the current time for today.');
                    }
                },
            ],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'reference_files.*' => ['nullable', 'file', 'mimes:png,jpg,jpeg,pdf,xlsx,xls,doc,docx', 'max:10240'],
        ];
    }

    protected function passedValidation(): void
    {
        if ($this->filled('due_date') && $this->filled('due_time')) {
            $this->merge([
                'due_date' => Carbon::parse($this->input('due_date') . ' ' . $this->input('due_time')),
            ]);
        }
    }
    
    public function messages(): array
    {
        return [
            'due_date.required' => 'Please choose a submission date.',
            'due_date.after_or_equal' => 'Submission date cannot be earlier than today.',
            'due_time.required' => 'Please choose a submission time.',
            'due_time.date_format' => 'Please choose a valid submission time.',
            'reference_files.*.mimes' => 'The file must be a file of type: png, jpeg, pdf, excel, docx.',
        ];
    }
}
