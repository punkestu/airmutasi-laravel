<?php

namespace App\Http\Controllers\Rotasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Pengajuan;
use Carbon\Carbon;

class SelektifAdminController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;
        $tab = $request->tab === null ? 'diajukan' : $request->tab;
        // get semua pengajuan
        $pengajuans = Pengajuan::where('status', $tab)->get()->map(function ($pengajuan) {
            $pengajuan->tanggal_pengajuan = Carbon::parse($pengajuan->created_at)->format('d-m-Y');
            return $pengajuan;
        });
        $query = "";
        // filter pengajuan
        if ($request->search_nama) {
            $query .= "&search_nama=" . $request->search_nama;
            $pengajuans = $pengajuans->filter(function ($pengajuan) use ($request) {
                return str_contains(strtolower($pengajuan->nama_lengkap), strtolower($request->search_nama));
            });
        }
        if ($request->nik) {
            $query .= "&nik=" . $request->nik;
            $pengajuans = $pengajuans->filter(function ($pengajuan) use ($request) {
                return $pengajuan->nik === $request->nik;
            });
        }
        if ($request->lokasi_awal) {
            $query .= "&lokasi_awal=" . $request->lokasi_awal;
            $pengajuans = $pengajuans->filter(function ($pengajuan) use ($request) {
                return $pengajuan->lokasiAwal->nama === $request->lokasi_awal;
            });
        }
        if ($request->lokasi_tujuan) {
            $query .= "&lokasi_tujuan=" . $request->lokasi_tujuan;
            $pengajuans = $pengajuans->filter(function ($pengajuan) use ($request) {
                return $pengajuan->lokasiTujuan->nama === $request->lokasi_tujuan;
            });
        }
        // get pengajuan yang dipilih
        if ($id !== null) {
            $pengajuan = Pengajuan::find($id);
            if (!$pengajuan || $pengajuan->status !== $tab) {
                return redirect()->route('rotasi.selektif');
            }
            $pengajuan->tanggal_pengajuan = Carbon::parse($pengajuan->created_at)->format('d-m-Y');
        } else {
            $pengajuan = $pengajuans->first();
        }
        return view('rotasi.selektif.index', ['pengajuans' => $pengajuans, 'pengajuan' => $pengajuan, 'query' => $query, 'tab' => $tab, 'cabangs' => Cabang::all(), 'request' => $request]);
    }

    public function selektif($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:dapat,tidak,diterima'
        ]);
        $pengajuan = Pengajuan::find($id);
        if (!$pengajuan) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        if ($request->status == 'dapat') {
            $pengajuan->status = 'dapat';
            $pengajuan->save();
            return redirect()->back()->with('success', 'Data berhasil diupdate');
        } else if ($request->status == 'tidak') {
            $request->validate([
                'keterangan' => 'required',
                'rekomendasi' => 'required'
            ]);
            $pengajuan->status = 'tidak_dapat';
            $pengajuan->save();
            return redirect()->back()->with('success', 'Data berhasil diupdate');
        } else if ($request->status == 'diterima') {
            $pengajuan->status = 'diterima';
            $pengajuan->save();
            
            if ($pengajuan->posisi_sekarang == "ACO") {
                $pengajuan->lokasiAwal->jumlah_personel_aco -= 1;
            } else {
                $pengajuan->lokasiAwal->jumlah_personel -= 1;
            }

            if ($pengajuan->posisi_tujuan == "ACO") {
                $pengajuan->lokasiTujuan->jumlah_personel_aco += 1;
            } else {
                $pengajuan->lokasiTujuan->jumlah_personel += 1;
            }
            $pengajuan->lokasiAwal->save();
            $pengajuan->lokasiTujuan->save();
            return redirect()->back()->with('success', 'Data berhasil diupdate');
        }
        return redirect()->back();
    }
}
