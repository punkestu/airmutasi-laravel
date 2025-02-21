<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'jabatan',
        'masa_kerja',
        'level_jabatan',
        'kontak',
        'cabang_id',
        'posisi',
        'pensiun',
        'tidak_pindah',
        'expired',
        'magang',
        
        "e_nik",
        "nik_ap1",
        "gelar",
        "kelamin",
        "tempat_lahir",
        "tgl_lahir",
        "usia_th",
        "usia_bl",
        "sts_karyawan",
        "tmt_kerja_airnav",
        "tmt_kerja_golongan",
        "tmt_pensiun",
        "lokasi",
        "cabangs",
        "lokasi_induk",
        "cabangs",
        "unit",
        "tmt_jabatan",
        "masa_kerja_jabatan_bl",
        "nama_level_jabatan",
        "tmt_level_jabatan",
        "masa_kerja_level_jabatan_th",
        "masa_kerja_level_jabatan_bl",
        "skala_jabatan",
        'fungsi',
        "job_text",

        "type",
    ];

    public function kompetensis()
    {
        return $this->hasMany(PersonelKompetensi::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function lokasiCabang()
    {
        return $this->belongsTo(Cabang::class, 'lokasi');
    }

    public function lokasiInduk()
    {
        return $this->belongsTo(Cabang::class, 'lokasi_induk');
    }

    public function pengajuan_pindah()
    {
        return $this->hasMany(Pengajuan::class, 'nik', 'nik')->where('status', '=', 'diajukan')->orderBy('created_at', 'desc');
    }
}
