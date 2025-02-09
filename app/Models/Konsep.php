<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsep extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "berkas",
        "cabang_id",
        'task_id',
        "type"
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
