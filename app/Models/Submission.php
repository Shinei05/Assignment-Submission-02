<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'student_id', 'status', 'file_path', 'original_file_name', 'submitted_at'];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
