<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'task_id',
        'is_read'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function notread()
    {
        return $this->where("is_read", false)->get();
    }
}
