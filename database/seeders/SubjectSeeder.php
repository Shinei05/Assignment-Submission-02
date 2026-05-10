<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            'Mathematics',
            'Science',
            'English Literature',
            'History',
            'Computer Science',
            'Physical Education',
            'Art and Design',
            'Geography',
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['name' => $subject]);
        }
    }
}
