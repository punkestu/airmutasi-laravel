<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasCabang extends Model
{
    use HasFactory;

    protected $fillable = ['kelas_id', 'cabang_id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
