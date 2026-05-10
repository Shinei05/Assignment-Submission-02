<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskReferenceFile extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'file_path', 'original_file_name'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
