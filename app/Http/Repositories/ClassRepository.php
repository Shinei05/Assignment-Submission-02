<?php

namespace App\Http\Repositories;

use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Collection;

class ClassRepository
{
    /**
     * Get all classes for a specific teacher.
     */
    public function getByTeacherId(int $teacherId): Collection
    {
        return SchoolClass::withCount('students', 'tasks')
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get();
    }

    /**
     * Find a class by ID.
     */
    public function find(int $classId): ?SchoolClass
    {
        return SchoolClass::with(['students', 'tasks'])->findOrFail($classId);
    }

    /**
     * Create a new class.
     */
    public function create(array $data): SchoolClass
    {
        return SchoolClass::create($data);
    }

    /**
     * Add students to a class.
     * We syncWithoutDetaching to ensure we don't accidentally remove existing ones
     * and don't duplicate entries if they are already in the class.
     */
    public function addStudents(SchoolClass $schoolClass, array $studentIds): void
    {
        $schoolClass->students()->syncWithoutDetaching($studentIds);
    }

    /**
     * Get the total count of classes for a teacher.
     */
    public function countByTeacherId(int $teacherId): int
    {
        return SchoolClass::where('teacher_id', $teacherId)->count();
    }
}
