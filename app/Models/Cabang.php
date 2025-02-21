<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'alamat',
        'thumbnail',
        'jumlah_personel',
        'formasi',
        'frms',
        'jumlah_personel_aco',
        'formasi_aco',
        'frms_aco',
        'jumlah_personel_ais',
        'formasi_ais',
        'frms_ais',
        'jumlah_personel_atfm',
        'formasi_atfm',
        'frms_atfm',
        'jumlah_personel_tapor',
        'formasi_tapor',
        'frms_tapor',
        'jumlah_personel_ats_system',
        'formasi_ats_system',
        'frms_ats_system',
        'jumlah_personel_cns',
        'formasi_cns',
        'frms_cns',
        'jumlah_personel_ess',
        'formasi_ess',
        'frms_ess',
        'jumlah_personel_staffumum',
        'formasi_staffumum',
        'frms_staffumum',
    ];

    private $jabatanATC;
    private $jabatanACO;
    private $jabatanAIS;
    private $jabatanATFM;
    private $jabatanTAPOR;
    private $jabatanATSSystem;
    private $jabatanCNS;
    private $jabatanESS;
    private $jabatanStaffUmum;

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'to')->orderBy('created_at', 'desc');
    }

    public function notreadnotifications()
    {
        return $this->hasMany(
            Notification::class,
            'to'
        )->where("is_read", false);
    }

    public function coord()
    {
        return $this->hasOne(CabangCoord::class);
    }

    public function personels()
    {
        return $this->hasMany(Personel::class);
    }

    public function personelPensiunATC()
    {
        if ($this->jabatanATC == null) {
            $this->jabatanATC = PersonelJabatanCategory::select('jabatan')->where('category', 'ATC')->get();
            $this->jabatanATC = $this->jabatanATC->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanATC);
    }

    public function personelPensiunACO()
    {
        if ($this->jabatanACO == null) {
            $this->jabatanACO = PersonelJabatanCategory::select('jabatan')->where('category', 'ACO')->get();
            $this->jabatanACO = $this->jabatanACO->map(function ($item) {
                return $item->jabatan;
            });
        }
        
        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanACO);
    }

    public function personelPensiunAIS()
    {
        if ($this->jabatanAIS == null) {
            $this->jabatanAIS = PersonelJabatanCategory::select('jabatan')->where('category', 'AIS')->get();
            $this->jabatanAIS = $this->jabatanAIS->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanAIS);
    }

    public function personelPensiunATFM()
    {
        if ($this->jabatanATFM == null) {
            $this->jabatanATFM = PersonelJabatanCategory::select('jabatan')->where('category', 'ATFM')->get();
            $this->jabatanATFM = $this->jabatanATFM->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanATFM);
    }

    public function personelPensiunTAPOR()
    {
        if ($this->jabatanTAPOR == null) {
            $this->jabatanTAPOR = PersonelJabatanCategory::select('jabatan')->where('category', 'TAPOR')->get();
            $this->jabatanTAPOR = $this->jabatanTAPOR->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanTAPOR);
    }

    public function personelPensiunATSSystem()
    {
        if ($this->jabatanATSSystem == null) {
            $this->jabatanATSSystem = PersonelJabatanCategory::select('jabatan')->where('category', 'ATSSystem')->get();
            $this->jabatanATSSystem = $this->jabatanATSSystem->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanATSSystem);
    }

    public function personelPensiunCNS()
    {
        if ($this->jabatanCNS == null) {
            $this->jabatanCNS = PersonelJabatanCategory::select('jabatan')->where('category', 'CNS')->get();
            $this->jabatanCNS = $this->jabatanCNS->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanCNS);
    }

    public function personelPensiunESS()
    {
        if ($this->jabatanESS == null) {
            $this->jabatanESS = PersonelJabatanCategory::select('jabatan')->where('category', 'ESS')->get();
            $this->jabatanESS = $this->jabatanESS->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanESS);
    }

    public function personelPensiunStaffUmum()
    {
        if ($this->jabatanStaffUmum == null) {
            $this->jabatanStaffUmum = PersonelJabatanCategory::select('jabatan')->where('category', 'StaffUmum')->get();
            $this->jabatanStaffUmum = $this->jabatanStaffUmum->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('pensiun', '=', 1)
            ->whereIn('posisi', $this->jabatanStaffUmum);
    }


    public function personelMagangATC()
    {
        if ($this->jabatanATC == null) {
            $this->jabatanATC = PersonelJabatanCategory::select('jabatan')->where('category', 'ATC')->get();
            $this->jabatanATC = $this->jabatanATC->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanATC);
    }

    public function personelMagangACO()
    {
        if ($this->jabatanACO == null) {
            $this->jabatanACO = PersonelJabatanCategory::select('jabatan')->where('category', 'ACO')->get();
            $this->jabatanACO = $this->jabatanACO->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanACO);
    }

    public function personelMagangAIS()
    {
        if ($this->jabatanAIS == null) {
            $this->jabatanAIS = PersonelJabatanCategory::select('jabatan')->where('category', 'AIS')->get();
            $this->jabatanAIS = $this->jabatanAIS->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanAIS);
    }

    public function personelMagangATFM()
    {
        if ($this->jabatanATFM == null) {
            $this->jabatanATFM = PersonelJabatanCategory::select('jabatan')->where('category', 'ATFM')->get();
            $this->jabatanATFM = $this->jabatanATFM->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanATFM);
    }

    public function personelMagangTAPOR()
    {
        if ($this->jabatanTAPOR == null) {
            $this->jabatanTAPOR = PersonelJabatanCategory::select('jabatan')->where('category', 'TAPOR')->get();
            $this->jabatanTAPOR = $this->jabatanTAPOR->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanTAPOR);
    }

    public function personelMagangATSSystem()
    {
        if ($this->jabatanATSSystem == null) {
            $this->jabatanATSSystem = PersonelJabatanCategory::select('jabatan')->where('category', 'ATSSystem')->get();
            $this->jabatanATSSystem = $this->jabatanATSSystem->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanATSSystem);
    }

    public function personelMagangCNS()
    {
        if ($this->jabatanCNS == null) {
            $this->jabatanCNS = PersonelJabatanCategory::select('jabatan')->where('category', 'CNS')->get();
            $this->jabatanCNS = $this->jabatanCNS->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanCNS);
    }

    public function personelMagangESS()
    {
        if ($this->jabatanESS == null) {
            $this->jabatanESS = PersonelJabatanCategory::select('jabatan')->where('category', 'ESS')->get();
            $this->jabatanESS = $this->jabatanESS->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanESS);
    }

    public function personelMagangStaffUmum()
    {
        if ($this->jabatanStaffUmum == null) {
            $this->jabatanStaffUmum = PersonelJabatanCategory::select('jabatan')->where('category', 'StaffUmum')->get();
            $this->jabatanStaffUmum = $this->jabatanStaffUmum->map(function ($item) {
                return $item->jabatan;
            });
        }

        return $this->hasMany(Personel::class)
            ->where('magang', '=', 1)
            ->whereIn('posisi', $this->jabatanStaffUmum);
    }

    public function kelases()
    {
        return $this->hasMany(KelasCabang::class);
    }

    public function inDapat()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->where('status', '=', 'dapat')
            ->where('updated_at', '>=', now()->subYears(1));
    }
    public function outDapat()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->where('status', '=', 'dapat')
            ->where('updated_at', '>=', now()->subYears(1));
    }

    public function inTidakDapat()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->where('status', '=', 'tidak')
            ->where('updated_at', '>=', now()->subYears(1));
    }
    public function outTidakDapat()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->where('status', '=', 'tidak')
            ->where('updated_at', '>=', now()->subYears(1));
    }

    public function inDiterima()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->where('status', '=', 'diterima')
            ->where('updated_at', '>=', now()->subYears(1));
    }
    public function outDiterima()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->where('status', '=', 'diterima')
            ->where('updated_at', '>=', now()->subYears(1));
    }

    public function inAll()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1));
    }
    public function outAll()
    {
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
        ;
    }

    public function in()
    {
        if ($this->jabatanATC == null) {
            $this->jabatanATC = PersonelJabatanCategory::select('jabatan')->where('category', 'ATC')->get();
            $this->jabatanATC = $this->jabatanATC->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_tujuan', $this->jabatanATC);
    }

    public function out()
    {
        if ($this->jabatanATC == null) {
            $this->jabatanATC = PersonelJabatanCategory::select('jabatan')->where('category', 'ATC')->get();
            $this->jabatanATC = $this->jabatanATC->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_sekarang', $this->jabatanATC);
    }

    public function inACO()
    {
        if ($this->jabatanACO == null) {
            $this->jabatanACO = PersonelJabatanCategory::select('jabatan')->where('category', 'ACO')->get();
            $this->jabatanACO = $this->jabatanACO->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_tujuan', $this->jabatanACO);
    }

    public function outACO()
    {
        if ($this->jabatanACO == null) {
            $this->jabatanACO = PersonelJabatanCategory::select('jabatan')->where('category', 'ACO')->get();
            $this->jabatanACO = $this->jabatanACO->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_sekarang', $this->jabatanACO);
    }

    public function inAIS()
    {
        if ($this->jabatanAIS == null) {
            $this->jabatanAIS = PersonelJabatanCategory::select('jabatan')->where('category', 'AIS')->get();
            $this->jabatanAIS = $this->jabatanAIS->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_tujuan', $this->jabatanAIS);
    }

    public function outAIS()
    {
        if ($this->jabatanAIS == null) {
            $this->jabatanAIS = PersonelJabatanCategory::select('jabatan')->where('category', 'AIS')->get();
            $this->jabatanAIS = $this->jabatanAIS->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_sekarang', $this->jabatanAIS);
    }

    public function inATFM()
    {
        if ($this->jabatanATFM == null) {
            $this->jabatanATFM = PersonelJabatanCategory::select('jabatan')->where('category', 'ATFM')->get();
            $this->jabatanATFM = $this->jabatanATFM->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_tujuan', $this->jabatanATFM);
    }

    public function outATFM()
    {
        if ($this->jabatanATFM == null) {
            $this->jabatanATFM = PersonelJabatanCategory::select('jabatan')->where('category', 'ATFM')->get();
            $this->jabatanATFM = $this->jabatanATFM->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_sekarang', $this->jabatanATFM);
    }

    public function inTAPOR()
    {
        if ($this->jabatanTAPOR == null) {
            $this->jabatanTAPOR = PersonelJabatanCategory::select('jabatan')->where('category', 'TAPOR')->get();
            $this->jabatanTAPOR = $this->jabatanTAPOR->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_tujuan', $this->jabatanTAPOR);
    }

    public function outTAPOR()
    {
        if ($this->jabatanTAPOR == null) {
            $this->jabatanTAPOR = PersonelJabatanCategory::select('jabatan')->where('category', 'TAPOR')->get();
            $this->jabatanTAPOR = $this->jabatanTAPOR->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_sekarang', $this->jabatanTAPOR);
    }

    public function inATSSystem()
    {
        if ($this->jabatanATSSystem == null) {
            $this->jabatanATSSystem = PersonelJabatanCategory::select('jabatan')->where('category', 'ATSSystem')->get();
            $this->jabatanATSSystem = $this->jabatanATSSystem->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_tujuan_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_tujuan', $this->jabatanATSSystem);
    }

    public function outATSSystem()
    {
        if ($this->jabatanATSSystem == null) {
            $this->jabatanATSSystem = PersonelJabatanCategory::select('jabatan')->where('category', 'ATSSystem')->get();
            $this->jabatanATSSystem = $this->jabatanATSSystem->map(function ($item) {
                return $item->jabatan;
            });
        }
        return $this->hasMany(Pengajuan::class, 'lokasi_awal_id')
            ->whereIn('status', ['diterima'])
            ->where('updated_at', '>=', now()->subYears(1))
            ->whereIn('posisi_sekarang', $this->jabatanATSSystem);
    }
}
