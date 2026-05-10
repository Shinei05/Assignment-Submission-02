<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'title', 'description', 'due_date', 'max_attempts'];

    protected $casts = [
        'due_date' => 'datetime',
        'max_attempts' => 'integer',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function referenceFiles()
    {
        return $this->hasMany(TaskReferenceFile::class, 'task_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'task_id');
    }
}
