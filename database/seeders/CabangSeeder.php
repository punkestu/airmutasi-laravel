<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\CabangCoord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // AnakCabang::create([
        //     'cabang_id' => 1,
        //     'nama' => 'Yogyakarta'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 1,
        //     'nama' => 'Sleman'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 1,
        //     'nama' => 'Bantul'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 2,
        //     'nama' => 'Surabaya'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 2,
        //     'nama' => 'Sidoarjo'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 2,
        //     'nama' => 'Gresik'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 3,
        //     'nama' => 'Semarang'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 3,
        //     'nama' => 'Kendal'
        // ]);
        // AnakCabang::create([
        //     'cabang_id' => 3,
        //     'nama' => 'Demak'
        // ]);
        Cabang::create([
            'nama' => 'Yogyakarta',
            'alamat' => 'Jl. Malioboro No. 1',
            'thumbnail' => '/cabang/yogyakarta.png',
            'jumlah_personel' => 100,
            'formasi' => 200,
            'frms' => 10,
        ]);
        CabangCoord::create([
            'cabang_id' => 1,
            'latitude' => -7.7956,
            'longitude' => 110.3695,
        ]);
        Cabang::create([
            'nama' => 'Sleman',
            'alamat' => 'Jl. Kaliurang No. 1',
            'thumbnail' => '/cabang/sleman.png',
            'jumlah_personel' => 200,
            'formasi' => 300,
            'frms' => 20,
        ]);
        Cabang::create([
            'nama' => 'Surabaya',
            'alamat' => 'Jl. Gubeng No. 1',
            'thumbnail' => '/cabang/surabaya.png',
            'jumlah_personel' => 300,
            'formasi' => 400,
            'frms' => 30,
        ]);
        CabangCoord::create([
            'cabang_id' => 3,
            'latitude' => -7.2575,
            'longitude' => 112.7521,
        ]);
        Cabang::create([
            'nama' => 'Semarang',
            'alamat' => 'Jl. Pemuda No. 1',
            'thumbnail' => '/cabang/semarang.png',
            'jumlah_personel' => 400,
            'formasi' => 500,
            'frms' => 40,
        ]);
        CabangCoord::create([
            'cabang_id' => 4,
            'latitude' => -6.9932,
            'longitude' => 110.4203,
        ]);
    }
}
