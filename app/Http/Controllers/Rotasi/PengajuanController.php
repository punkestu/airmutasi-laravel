<?php

namespace App\Http\Controllers\Rotasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Notification;
use App\Models\Pengajuan;
use App\Models\Personel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Barryvdh\DomPDF\Facade\Pdf;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class PengajuanController extends Controller
{
    private $posisi = ['ATC (TWR)', 'ATC (APS)', 'ATC (ACS)', 'ACO', 'AIS', 'ATFM', 'TAPOR', 'ATSSystem', 'STAFF'];

    public function inputView()
    {
        if (auth()->user()->role->name !== 'admin') {
            if (!auth()->user()->profile || !auth()->user()->profile->cabang_id) {
                return redirect()->back();
            }
            $usersCabangs = Cabang::where('id', auth()->user()->profile->cabang_id)->get();
            return view('rotasi.pengajuan.input', ['cabangs' => $usersCabangs]);
        }
        $cabangs = Cabang::all();
        return view('rotasi.pengajuan.input', ['cabangs' => $cabangs]);
    }

    public function input(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'nik' => 'required|numeric',
            'masa_kerja' => 'required',
            'jabatan' => 'required',
        ]);
        if ($request->has('tidak_pindah') && $request->tidak_pindah == 'ya') {
            $personel = Personel::where('nik', $request->nik)->first();
            $personel->update([
                'tidak_pindah' => true,
                'expired' => Carbon::now()->addYear()
            ]);
            return redirect()->back()->with('success', 'Data berhasil disimpan');
        }
        $request->validate([
            'lokasi_awal_id' => 'required|numeric|exists:cabangs,id',
            'lokasi_tujuan_id' => 'required|numeric|exists:cabangs,id',
            'posisi_sekarang' => 'required',
            'posisi_tujuan' => 'required',
            'kompetensi' => 'required|array',
            'kompetensi.*.nama' => 'required',
            'tujuan_rotasi' => 'required',
            'keterangan' => Rule::requiredIf(function () use ($request) {
                return $request->has('abnormal');
            }),
            'lokasi_tujuan_alt_id' => 'required_if:use_tujuan_alt,1|numeric|exists:cabangs,id',
            'lokasi_tujuan_alt2_id' => 'required_if:use_tujuan_alt,1|numeric|exists:cabangs,id',
        ]);

        if (auth()->user()->role->name !== 'admin') {
            if (!auth()->user()->profile->cabang_id) {
                return redirect()->back()->with('invalid', 'Anda tidak memiliki cabang')->withInput();
            }
            if ($request->lokasi_awal_id != auth()->user()->profile->cabang_id) {
                return redirect()->back()->with('invalid', 'Cabang asal tidak sesuai')->withInput();
            }
        }

        $pengajuan = $request->only([
            'lokasi_awal_id',
            'lokasi_tujuan_id',
            'nama_lengkap',
            'nik',
            'masa_kerja',
            'jabatan',
            'posisi_sekarang',
            'posisi_tujuan',
            'tujuan_rotasi',
            'keterangan',
        ]);
        if ($pengajuan['keterangan'] === null) {
            $pengajuan['keterangan'] = '';
        }
        if (!$request->has('abnormal')) {
            // bisa di ringkas

            $kelasCabangAwal = Cabang::with(['kelases' => function ($query) {
                $query->select(['cabang_id', 'kelas_id']);
            }])->find($request->lokasi_awal_id);
            $kelasCabangTujuan = Cabang::with(['kelases' => function ($query) {
                $query->select(['cabang_id', 'kelas_id']);
            }])->find($request->lokasi_tujuan_id);
            $intersect = $kelasCabangAwal->kelases->intersect($kelasCabangTujuan->kelases);
            if ($intersect->isEmpty()) {
                return redirect()->back()->with('invalid', 'Cabang asal dan tujuan tidak memiliki kelas yang sama')->withInput();
            }
            // sampai sini
        }
        $pengajuan['sk_mutasi_url'] = $request->sk_mutasi_url;
        $pengajuan['surat_persetujuan_url'] = $request->surat_persetujuan_url;
        DB::beginTransaction();
        $pengajuan['status'] = 'diajukan';
        $kompetensi = array_map(function ($kom) {
            if (!(str_contains($kom["url"], "http://") || str_contains($kom["url"], "https://")) && $kom["url"] !== null) {
                $kom["url"] = "http://" . $kom["url"];
            }
            $kom["file_url"] = $kom["url"];
            return ['nama' => $kom["nama"], 'file_url' => $kom["file_url"]];
        }, $request->kompetensi);
        if ($request->has('use_tujuan_alt')) {
            $pengajuanAlt = Pengajuan::create([
                'lokasi_awal_id' => $pengajuan['lokasi_awal_id'],
                'lokasi_tujuan_id' => $request['lokasi_tujuan_alt_id'],
                'nama_lengkap' => $pengajuan['nama_lengkap'],
                'nik' => $pengajuan['nik'],
                'masa_kerja' => $pengajuan['masa_kerja'],
                'jabatan' => $pengajuan['jabatan'],
                'posisi_sekarang' => $pengajuan['posisi_sekarang'],
                'posisi_tujuan' => $pengajuan['posisi_tujuan'],
                'tujuan_rotasi' => $pengajuan['tujuan_rotasi'],
                'keterangan' => $pengajuan['keterangan'],
                'sk_mutasi_url' => $pengajuan['sk_mutasi_url'],
                'surat_persetujuan_url' => $pengajuan['surat_persetujuan_url'],
                'status' => $pengajuan['status'],
            ]);
            $pengajuanAlt->kompetensis()->createMany($kompetensi);
            $pengajuan["secondary_pengajuan_id"] = $pengajuanAlt->id;
            Notification::create([
                'status' => $pengajuan['status'],
                'to' => null,
                'cabang_asal_id' => $pengajuan['lokasi_awal_id'],
                'cabang_tujuan_id' => $request['lokasi_tujuan_alt_id'],
                'pengajuan_id' => $pengajuanAlt->id
            ]);
        } else {
            $pengajuan["secondary_pengajuan_id"] = null;
        }
        if ($request->has('use_tujuan_alt2')) {
            $pengajuanAlt2 = Pengajuan::create([
                'lokasi_awal_id' => $pengajuan['lokasi_awal_id'],
                'lokasi_tujuan_id' => $request['lokasi_tujuan_alt2_id'],
                'nama_lengkap' => $pengajuan['nama_lengkap'],
                'nik' => $pengajuan['nik'],
                'masa_kerja' => $pengajuan['masa_kerja'],
                'jabatan' => $pengajuan['jabatan'],
                'posisi_sekarang' => $pengajuan['posisi_sekarang'],
                'posisi_tujuan' => $pengajuan['posisi_tujuan'],
                'tujuan_rotasi' => $pengajuan['tujuan_rotasi'],
                'keterangan' => $pengajuan['keterangan'],
                'sk_mutasi_url' => $pengajuan['sk_mutasi_url'],
                'surat_persetujuan_url' => $pengajuan['surat_persetujuan_url'],
                'status' => $pengajuan['status'],
            ]);
            $pengajuanAlt2->kompetensis()->createMany($kompetensi);
            $pengajuan["th3_pengajuan_id"] = $pengajuanAlt2->id;
            Notification::create([
                'status' => $pengajuan['status'],
                'to' => null,
                'cabang_asal_id' => $pengajuan['lokasi_awal_id'],
                'cabang_tujuan_id' => $request['lokasi_tujuan_alt2_id'],
                'pengajuan_id' => $pengajuanAlt2->id
            ]);
        } else {
            $pengajuan["th3_pengajuan_id"] = null;
        }
        $pengajuan = Pengajuan::create([
            'lokasi_awal_id' => $pengajuan['lokasi_awal_id'],
            'lokasi_tujuan_id' => $pengajuan['lokasi_tujuan_id'],
            'nama_lengkap' => $pengajuan['nama_lengkap'],
            'nik' => $pengajuan['nik'],
            'masa_kerja' => $pengajuan['masa_kerja'],
            'jabatan' => $pengajuan['jabatan'],
            'posisi_sekarang' => $pengajuan['posisi_sekarang'],
            'posisi_tujuan' => $pengajuan['posisi_tujuan'],
            'tujuan_rotasi' => $pengajuan['tujuan_rotasi'],
            'keterangan' => $pengajuan['keterangan'],
            'sk_mutasi_url' => $pengajuan['sk_mutasi_url'],
            'surat_persetujuan_url' => $pengajuan['surat_persetujuan_url'],
            'status' => $pengajuan['status'],
            'secondary_pengajuan_id' => $pengajuan["secondary_pengajuan_id"],
            'th3_pengajuan_id' => $pengajuan['th3_pengajuan_id']
        ]);
        $pengajuan->kompetensis()->createMany($kompetensi);
        Notification::create([
            'status' => $pengajuan['status'],
            'to' => null,
            'cabang_asal_id' => $pengajuan['lokasi_awal_id'],
            'cabang_tujuan_id' => $pengajuan['lokasi_tujuan_id'],
            'pengajuan_id' => $pengajuan->id
        ]);
        DB::commit();

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function updateView($id)
    {
        $cabangs = Cabang::all();
        $pengajuan = Pengajuan::with(['lokasiAwal', 'lokasiTujuan'])->find($id);
        if (!$pengajuan) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $pengajuan->tanggal_pengajuan = Carbon::parse($pengajuan->created_at)->format('d-m-Y');
        $pengajuan->lokasi_awal = $pengajuan->lokasiAwal;
        $pengajuan->lokasi_tujuan = $pengajuan->lokasiTujuan;
        return view('rotasi.pengajuan.update', ['pengajuan' => $pengajuan, 'cabangs' => $cabangs]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lokasi_awal_id' => 'required',
            'lokasi_tujuan_id' => 'required',
            'nama_lengkap' => 'required',
            'nik' => 'required|numeric',
            'masa_kerja' => 'required',
            'jabatan' => 'required',
            'posisi_sekarang' => ['required', Rule::in($this->posisi)],
            'posisi_tujuan' => ['required', Rule::in($this->posisi)],
            'kompetensi' => 'required|array',
            'kompetensi.*.nama' => 'required',
            'tujuan_rotasi' => 'required',
            'keterangan' => 'required',
        ]);

        $pengajuanR = $request->only([
            'lokasi_awal_id',
            'lokasi_tujuan_id',
            'nama_lengkap',
            'nik',
            'masa_kerja',
            'jabatan',
            'posisi_sekarang',
            'posisi_tujuan',
            'tujuan_rotasi',
            'keterangan',
        ]);
        DB::beginTransaction();
        $pengajuan = Pengajuan::find($id);
        if ($request->sk_mutasi_url) {
            $pengajuanR['sk_mutasi_url'] = $request->sk_mutasi_url;
        } else {
            $pengajuanR['sk_mutasi_url'] = $pengajuan->sk_mutasi_url;
        }
        if ($request->surat_persetujuan_url) {
            $pengajuanR['surat_persetujuan_url'] = $request->surat_persetujuan_url;
        } else {
            $pengajuanR['surat_persetujuan_url'] = $pengajuan->surat_persetujuan_url;
        }
        $pengajuan->update([
            'lokasi_awal_id' => $pengajuanR['lokasi_awal_id'],
            'lokasi_tujuan_id' => $pengajuanR['lokasi_tujuan_id'],
            'nama_lengkap' => $pengajuanR['nama_lengkap'],
            'nik' => $pengajuanR['nik'],
            'masa_kerja' => $pengajuanR['masa_kerja'],
            'jabatan' => $pengajuanR['jabatan'],
            'posisi_sekarang' => $pengajuanR['posisi_sekarang'],
            'posisi_tujuan' => $pengajuanR['posisi_tujuan'],
            'tujuan_rotasi' => $pengajuanR['tujuan_rotasi'],
            'keterangan' => $pengajuanR['keterangan'],
            'sk_mutasi_url' => $pengajuanR['sk_mutasi_url'],
            'surat_persetujuan_url' => $pengajuanR['surat_persetujuan_url'],
        ]);
        $kompetensi = array_map(function ($kom) {
            if (isset($kom["file_url"])) {
                $kom["file_url"] = $kom["file_url"];
            }
            if (isset($kom["url"]) && $kom["url"] !== null) {
                if (!(str_contains($kom["url"], "http://") || str_contains($kom["url"], "https://"))) {
                    $kom["url"] = "http://" . $kom["url"];
                }
                $kom["file_url"] = $kom["url"];
            } else $kom["file_url"] = null;
            return ['nama' => $kom["nama"], 'file_url' => $kom["file_url"]];
        }, $request->kompetensi, array_keys($request->kompetensi));
        $pengajuan->kompetensis()->delete();
        $pengajuan->kompetensis()->createMany($kompetensi);
        DB::commit();

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function document($id)
    {
        $pengajuan = Pengajuan::with(['lokasiAwal', 'lokasiTujuan', 'kompetensis'])->find($id);

        if (!$pengajuan) {
            abort(404);
        }

        $oMerger = PDFMergerFacade::init();
        $pdf = Pdf::loadView('documenttpl.pengajuan', ['pengajuan' => $pengajuan]);

        $pdf->setPaper('a4', 'portrait');
        $pdfPath = storage_path('tmp/pengajuan-' . $pengajuan->id . '.pdf');
        $pdf->save($pdfPath);
        $oMerger->addPDF($pdfPath, 'all');
        if ($pengajuan->sk_mutasi_url) {
            $path = storage_path('app/public/' . explode("storage", $pengajuan->sk_mutasi_url)[1]);
            $oMerger->addPDF($path, 'all');
        }
        if ($pengajuan->surat_persetujuan_url) {
            $path = storage_path('app/public/' . explode("storage", $pengajuan->surat_persetujuan_url)[1]);
            $oMerger->addPDF($path, 'all');
        }
        foreach ($pengajuan->kompetensis as $kompetensi) {
            if ($kompetensi->file_url) {
                $path = storage_path('app/public/' . explode("storage", $kompetensi->file_url)[1]);
                $oMerger->addPDF($path, 'all');
            }
        }

        $oMerger->merge();

        return $oMerger->stream();
    }
}
