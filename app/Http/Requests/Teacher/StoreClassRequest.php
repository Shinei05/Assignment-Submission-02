<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\SchoolClass;

class StoreClassRequest extends FormRequest
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
            'subject_id' => ['required', 'exists:subjects,id'],
            'day_of_week' => ['required', 'string', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $teacher = $this->user();
            
            if ($teacher->teacherClasses()->count() >= 3) {
                $validator->errors()->add('base', 'You can only create a maximum of 3 classes.');
            }

            // Check global uniqueness of schedule (overlap check)
            // A schedule conflicts if a class exists on the same day where
            // existing_start_time < new_end_time AND existing_end_time > new_start_time
            $scheduleExists = SchoolClass::where('day_of_week', $this->day_of_week)
                ->where('start_time', '<', $this->end_time)
                ->where('end_time', '>', $this->start_time)
                ->exists();

            if ($scheduleExists) {
                $validator->errors()->add('base', 'This schedule conflicts with an existing class schedule.');
            }
        });
    }
}
