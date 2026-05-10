<?php

namespace App\Http\Services;

use App\Http\Repositories\ClassRepository;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Collection;

class ClassService
{
    public function __construct(private ClassRepository $classRepository)
    {
    }

    /**
     * Get all classes for a specific teacher.
     */
    public function getClassesForTeacher(int $teacherId): Collection
    {
        return $this->classRepository->getByTeacherId($teacherId);
    }

    /**
     * Find a specific class.
     */
    public function getClassDetails(int $classId): ?SchoolClass
    {
        return $this->classRepository->find($classId);
    }

    /**
     * Create a new class and assign the teacher.
     */
    public function createClass(int $teacherId, array $data): SchoolClass
    {
        $data['teacher_id'] = $teacherId;
        return $this->classRepository->create($data);
    }

    /**
     * Add multiple students to a class.
     */
    public function addStudentsToClass(int $classId, array $studentIds): void
    {
        $schoolClass = $this->classRepository->find($classId);
        $this->classRepository->addStudents($schoolClass, $studentIds);
    }
}
