<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Konsep;
use App\Models\Personel;
use App\Models\PersonelJabatanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonelController extends Controller
{
    public function index()
    {
        $page = request()->get('page', 0);
        $limit = 100;
        $nik = request()->get('nik');
        $name = request()->get('nama');
        $cabang_id = request()->get('cabang_id');
        $cabang = request()->get('cabang');
        if ($nik == "" && $name == "" && $cabang == "") {
            $personels = Personel::with(['cabang', 'lokasiCabang', 'lokasiInduk', 'pengajuan_pindah' => [
                'lokasiAwal',
                'lokasiTujuan',
            ]])->limit($limit)->offset($page * $limit)->get();
            $cabangs = Cabang::all();
            if (!$cabangs) abort(404);
            return view('personel.index', [
                'personels' => $personels,
                'cabangs' => $cabangs,
                'page' => $page ?? 0,
                'search' => [
                    'nik' => $nik,
                    'name' => $name,
                    'cabang_id' => $cabang_id,
                    'cabang' => $cabang,
                ],
            ]);
        }
        $personels = Personel::with(['cabang', 'lokasiCabang', 'lokasiInduk', 'pengajuan_pindah' => [
            'lokasiAwal',
            'lokasiTujuan',
        ]])->where('nik', 'like', $nik . '%')->where('name', 'like', '%' . $name . '%');
        if ($cabang != "") {
            $personels = $personels->where('cabang_id', $cabang_id);
        }
        $personels = $personels->limit($limit)->offset($page * $limit)->get();
        $search = [
            'nik' => $nik,
            'name' => $name,
            'cabang_id' => $cabang_id,
            'cabang' => $cabang,
        ];

        if ($personels->count() === 0 && $page > 0) {
            return redirect()->back();
        }

        $cabangs = Cabang::all();
        if (!$cabangs) abort(404);
        return view('personel.index', [
            'personels' => $personels,
            'cabangs' => $cabangs,
            'page' => $page ?? 0,
            'search' => $search,
        ]);
    }

    public function search_by_nik(Request $request)
    {
        $nik = $request->nik;
        $personels = Personel::where('nik', 'like', $nik . '%')->get();
        return response()->json($personels);
    }

    public function cabang(Request $request, $id)
    {
        $page = request()->get('page', 0);
        $tab = request()->get('tab', 'ATC');
        $limit = 100;
        $jabatan = PersonelJabatanCategory::all()->groupBy('category');
        $jabatan = $jabatan->map(function ($jabatan) {
            return $jabatan->pluck('jabatan')->toArray();
        })->toArray();
        $categories = array_keys($jabatan);
        $jabatans = PersonelJabatanCategory::all()->select('jabatan');
        if ($request->tab === 'ACO') {
            $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatan) {
                $query->with(['kompetensis', 'pengajuan_pindah' => [
                    'lokasiAwal',
                    'lokasiTujuan',
                ]])->where(function ($query) use ($jabatan) {
                    //$query->where('posisi', 'AERONAUTICAL COMMUNICATION OFFICER')->orWhere('posisi', 'ACO');
                    $query->whereIn('posisi', $jabatan['ACO'] ?? [])->orWhere('posisi', 'LIKE', 'ACO%');
                })->limit($limit)->offset($page * $limit);
            }])->find($id);
        } else if ($request->tab === 'AIS') {
            $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatan) {
                $query->with(['kompetensis', 'pengajuan_pindah' => [
                    'lokasiAwal',
                    'lokasiTujuan',
                ]])->where(function ($query) use ($jabatan) {
                    //$query->where('posisi', 'AIS')->orWhere('posisi', 'AERONAUTICAL INFORMATION SERVICE')->orWhere('posisi', 'LIKE', 'AIS%');
                    $query->whereIn('posisi', $jabatan['AIS'] ?? [])->orWhere('posisi', 'LIKE', 'AIS%');
                })->limit($limit)->offset($page * $limit);
            }])->find($id);
        } else if ($request->tab === 'ATFM') {
            $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatan) {
                $query->with(['kompetensis', 'pengajuan_pindah' => [
                    'lokasiAwal',
                    'lokasiTujuan',
                ]])->where(function ($query) use ($jabatan) {
                    //$query->where('posisi', 'ATFM')->orWhere('posisi', 'AIR TRAFFIC FLOW MANAGEMENT')->orWhere('posisi', 'STAF ATFM');
                    $query->whereIn('posisi', $jabatan['ATFM'] ?? [])->orWhere('posisi', 'LIKE', 'ATFM%');
                })->limit($limit)->offset($page * $limit);
            }])->find($id);
        } else if ($request->tab === 'TAPOR') {
            $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatan) {
                $query->with(['kompetensis', 'pengajuan_pindah' => [
                    'lokasiAwal',
                    'lokasiTujuan',
                ]])->where(function ($query) use ($jabatan) {
                    //$query->where('posisi', 'TAPOR')->orWhere('posisi', 'STAF PELAPORAN DATA');
                    $query->whereIn('posisi', $jabatan['TAPOR'] ?? [])->orWhere('posisi', 'LIKE', 'TAPOR%');
                })->limit($limit)->offset($page * $limit);
            }])->find($id);
        } else if ($request->tab === 'ATSSystem') {
            $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatan) {
                $query->with(['kompetensis', 'pengajuan_pindah' => [
                    'lokasiAwal',
                    'lokasiTujuan',
                ]])->where(function ($query) use ($jabatan) {
                    //$query->where('posisi', 'ATSSystem')->orWhere('posisi', 'AIR TRAFFIC SERVICES SYSTEM')->orWhere('posisi', 'SPESIALIS ATS SYSTEM');
                    $query->whereIn('posisi', $jabatan['ATSSystem'] ?? [])->orWhere('posisi', 'LIKE', 'ATSSystem%');
                })->limit($limit)->offset($page * $limit);
            }])->find($id);
        } else if ($request->tab === 'ATC') {
            $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatan) {
                $query->with(['kompetensis', 'pengajuan_pindah' => [
                    'lokasiAwal',
                    'lokasiTujuan',
                ]])->where(function ($query) use ($jabatan) {
                    //$query->where('posisi', 'LIKE', 'ATC%')->orWhere('posisi', 'AIR TRAFFIC CONTROLLER');
                    $query->whereIn('posisi', $jabatan['ATC'] ?? [])->orWhere('posisi', 'LIKE', 'ATC%');
                })->limit($limit)->offset($page * $limit);
            }])->find($id);
        } else {
            if ($request->tab && array_key_exists($request->tab, $jabatan)) {
                $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatan, $request) {
                    $query->with(['kompetensis', 'pengajuan_pindah' => [
                        'lokasiAwal',
                        'lokasiTujuan',
                    ]])->where(function ($query) use ($jabatan, $request) {
                        $query->whereIn('posisi', $jabatan[$request->tab] ?? []);
                    })->limit($limit)->offset($page * $limit);
                }])->find($id);
            } else {
                $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page, $jabatans) {
                    $query->with(['kompetensis', 'pengajuan_pindah' => [
                        'lokasiAwal',
                        'lokasiTujuan',
                    ]])->where(function ($query) use ($jabatans) {
                        //$query->whereNotIn('posisi', ['ATC (APS)', 'ACO', 'AIS', 'ATFM', 'TAPOR', 'ATSSystem', 'AERONAUTICAL COMMUNICATION OFFICER', 'AERONAUTICAL INFORMATION SERVICE', 'AIR TRAFFIC FLOW MANAGEMENT', 'TOWER APPROACH', 'STAF PELAPORAN DATA', 'AIR TRAFFIC SERVICES SYSTEM', 'AIR TRAFFIC CONTROLLER'])->whereNot('posisi', 'LIKE', 'ATC%')->whereNot('posisi', 'LIKE', 'AIS%');
                        $query->whereNotIn('posisi', $jabatans)->whereNot('posisi', 'LIKE', 'ATC%')->whereNot('posisi', 'LIKE', 'AIS%')->whereNot('posisi', 'LIKE', 'ATFM%')->whereNot('posisi', 'LIKE', 'TAPOR%')->whereNot('posisi', 'LIKE', 'ATSSystem%')->whereNot('posisi', 'LIKE', 'ACO%');
                    })->limit($limit)->offset($page * $limit);
                }])->find($id);
            }
        }
        if (!$cabang) abort(404);
        if ($cabang->nama === 'PUSAT INFORMASI AERONAUTIKA') {
            if ($request->tab != "AIS") $cabang->personels = [];
            else {
                $cabang = Cabang::with(['personels' => function ($query) use ($limit, $page) {
                    $query->with(['pengajuan_pindah' => [
                        'lokasiAwal',
                        'lokasiTujuan',
                    ]])->limit($limit)->offset($page * $limit);
                }])->find($id);
            }
        }
        return view('personel.cabang', [
            'cabang' => $cabang,
            'tab' => $request->tab ?? 'ATC',
            'page' => $page ?? 0,
            'categories' => $categories,
        ]);
    }

    public function inputView()
    {
        $cabangs = Cabang::all();
        return view('personel.input', [
            'cabangs' => $cabangs,
        ]);
    }

    public function importAllWithCsv(Request $request)
    {
        $request->validate([
            'sheet' => 'required|mimes:csv,txt',
        ]);
        $file = $request->file('sheet');
        $cabangs = Cabang::pluck('id', 'nama')->toArray();
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        $map_cabang = [
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH JAKARTA" => "KANTOR CABANG JATSC",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH MAKASSAR" => "KANTOR CABANG MATSC",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH DENPASAR" => "KANTOR CABANG DENPASAR",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH SURABAYA" => "KANTOR CABANG SURABAYA",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH MEDAN" => "KANTOR CABANG MEDAN",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH BALIKPAPAN" => "KANTOR CABANG BALIKPAPAN",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH PALEMBANG" => "KANTOR CABANG PALEMBANG",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH MANADO" => "KANTOR CABANG MANADO",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH SORONG" => "KANTOR CABANG SORONG",
            "UNIT PELAYANAN INFORMASI AERONAUTIKA WILAYAH SENTANI" => "KANTOR CABANG SENTANI",
            "KANTOR PUSAT" => "KANTOR PUSAT AIRNAV INDONESIA",
            "CABANG YOGYAKARTA" => "CABANG YOGYAKARTA (YIA)",
            "CABANG PEMBANTU TUAL, KAREL SADSUITUBUN" => "CABANG PEMBANTU TUAL"
        ];
        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                $dataPersonel = array_combine($header, $row);
                if (array_key_exists($dataPersonel['lokasi_kedudukan'], $map_cabang)) {
                    $dataPersonel['lokasi_kedudukan'] = $map_cabang[$dataPersonel['lokasi_kedudukan']];
                }
                if (array_key_exists($dataPersonel['lokasi'], $map_cabang)) {
                    $dataPersonel['lokasi'] = $map_cabang[$dataPersonel['lokasi']];
                }
                if (array_key_exists($dataPersonel['lokasi_induk'], $map_cabang)) {
                    $dataPersonel['lokasi_induk'] = $map_cabang[$dataPersonel['lokasi_induk']];
                }
                if (array_key_exists('KANTOR ' . $dataPersonel["lokasi_induk"], $cabangs)) {
                    $dataPersonel['lokasi_induk'] = $cabangs["KANTOR " . $dataPersonel["lokasi_induk"]];
                } else if (array_key_exists($dataPersonel["lokasi_induk"], $cabangs)) {
                    $dataPersonel['lokasi_induk'] = $cabangs[$dataPersonel["lokasi_induk"]];
                } else {
                    continue;
                }
                if (array_key_exists('KANTOR ' . $dataPersonel["lokasi"], $cabangs)) {
                    $dataPersonel['cabang_id'] = $cabangs["KANTOR " . $dataPersonel["lokasi"]];
                } else if (array_key_exists($dataPersonel["lokasi"], $cabangs)) {
                    $dataPersonel['cabang_id'] = $cabangs[$dataPersonel["lokasi"]];
                } else {
                    $dataPersonel['cabang_id'] = $dataPersonel['lokasi_induk'];
                }
                if (array_key_exists('KANTOR ' . $dataPersonel["lokasi_kedudukan"], $cabangs)) {
                    $dataPersonel['lokasi'] = $cabangs["KANTOR " . $dataPersonel["lokasi_kedudukan"]];
                } else if (array_key_exists($dataPersonel["lokasi_kedudukan"], $cabangs)) {
                    $dataPersonel['lokasi'] = $cabangs[$dataPersonel["lokasi_kedudukan"]];
                } else {
                    $dataPersonel['lokasi'] = $dataPersonel['lokasi_induk'];
                }
                $dataPersonel['posisi'] = $dataPersonel['jabatan'];
                $dataPersonel['jabatan'] = $dataPersonel['nama_level_jabatan'];
                $dataPersonel['nik'] = $dataPersonel['nik_airnav'];
                $dataPersonel['name'] = $dataPersonel['nama'];
                $dataPersonel['masa_kerja'] = $dataPersonel['masa_kerja_jabatan_th'];
                $dataPersonel['nama_level_jabatan'] = $dataPersonel['nama_level_jabatan'];
                $dataPersonel['pensiun'] = $dataPersonel['sts_karyawan'] === 'Pensiun';
                $dataPersonel['magang'] = $dataPersonel['sts_karyawan'] === 'Magang' || $dataPersonel['sts_karyawan'] === 'MAGANG';
                $dataPersonel['kontak'] = $dataPersonel['kontak'] ?? "-";

                $dataPersonel['masa_kerja_level_jabatan_th'] = $dataPersonel['masa_kerja_level_jabatan_th'] ? $dataPersonel['masa_kerja_level_jabatan_th'] : "0";
                $dataPersonel['masa_kerja_level_jabatan_bl'] = $dataPersonel['masa_kerja_level_jabatan_bl'] ? $dataPersonel['masa_kerja_level_jabatan_bl'] : "0";
                $dataPersonel['skala_jabatan'] = $dataPersonel['skala_jabatan'] ? $dataPersonel['skala_jabatan'] : "0";

                $dataPersonel["tgl_lahir"] = $dataPersonel["tgl_lahir"] ? date('Y-m-d', strtotime($dataPersonel["tgl_lahir"])) : "2000-01-01";
                $dataPersonel["tmt_kerja_airnav"] = $dataPersonel["tmt_kerja_airnav"] ? date('Y-m-d', strtotime($dataPersonel["tmt_kerja_airnav"])) : "2000-01-01";
                $dataPersonel["tmt_kerja_golongan"] = $dataPersonel["tmt_kerja_golongan"] ? date('Y-m-d', strtotime($dataPersonel["tmt_kerja_golongan"])) : "2000-01-01";
                $dataPersonel["tmt_pensiun"] = $dataPersonel["tmt_pensiun"] ? date('Y-m-d', strtotime($dataPersonel["tmt_pensiun"])) : "2000-01-01";
                $dataPersonel["tmt_jabatan"] = $dataPersonel["tmt_jabatan"] ? date('Y-m-d', strtotime($dataPersonel["tmt_jabatan"])) : "2000-01-01";
                $dataPersonel["tmt_level_jabatan"] = $dataPersonel["tmt_level_jabatan"] ? date('Y-m-d', strtotime($dataPersonel["tmt_level_jabatan"])) : "2000-01-01";

                Personel::updateOrCreate(["nik" => $dataPersonel["nik"]], $dataPersonel);
            }
            DB::commit();
            return redirect()->route('personel')->with('success', 'Personel berhasil diimport');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('personel')->with('error', 'Personel gagal diimport');
        }
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'sheet' => 'required|mimes:csv,txt',
            'cabang_id' => 'required',
            'posisi' => 'required',
        ]);
        $file = $request->file('sheet');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);
        $dataPersonels = [];
        foreach ($data as $row) {
            $dataPersonel = array_combine($header, $row);
            $dataPersonel['nik'] = $dataPersonel['nik_airnav'];
            $dataPersonel['cabang_id'] = $request->cabang_id;
            $dataPersonel['posisi'] = $request->posisi;
            $dataPersonels[] = $dataPersonel;
        }
        DB::beginTransaction();
        foreach ($dataPersonels as $dataPersonel) {
            $personel = Personel::create($dataPersonel);
            $personel->kompetensis()->createMany(array_map(function ($kompetensi) {
                return ['kompetensi' => $kompetensi];
            }, explode(',', $dataPersonel['kompetensi'])));
        }
        DB::commit();
        return redirect()->route('personel.index', ['id' => $request->cabang_id])->with('success', 'Personel berhasil diimport');
    }

    public function input(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:personels,nik',
            'name' => 'required',
            'jabatan' => 'required',
            'masa_kerja' => 'required|numeric',
            'level_jabatan' => 'required',
            'kontak' => 'required',
            'cabang_id' => 'required',
            'posisi' => 'required',
            'kompetensi' => 'required|array',
        ]);
        $dataPersonel = $request->only([
            'nik',
            'name',
            'jabatan',
            'masa_kerja',
            'level_jabatan',
            'kontak',
            'cabang_id',
            'posisi',
            'pensiun'
        ]);
        $dataPersonel["pensiun"] = $request->pensiun === 'on';
        DB::beginTransaction();
        $personel = Personel::create($dataPersonel);
        $personel->kompetensis()->createMany(array_map(function ($kompetensi) {
            return ['kompetensi' => $kompetensi];
        }, $request->kompetensi));
        DB::commit();
        return redirect()->route('personel.index', ['id' => $request->cabang_id])->with('success', 'Personel berhasil ditambahkan');
    }

    public function delete($id)
    {
        $personel = Personel::find($id);
        if (!$personel) abort(404);
        $cabang_id = $personel->cabang_id;
        $personel->delete();
        return redirect()->route('personel.index', ['id' => $cabang_id])->with('success', 'Personel berhasil dihapus');
    }

    public function togglePensiun($id)
    {
        $personel = Personel::find($id);
        if (!$personel) abort(404);
        $personel->update([
            'pensiun' => !$personel->pensiun
        ]);
        return redirect()->route('personel.index', ['id' => $personel->cabang_id])->with('success', 'Status pensiun personel berhasil diubah');
    }

    public function konsep()
    {
        $konseps = Konsep::where('cabang_id', null)->get();
        return view('personel.konsep', ["konseps" => $konseps]);
    }

    public function uploadKonsep(Request $request)
    {
        $request->validate([
            "name" => "required",
            "berkas" => "file|mimes:pdf,jpg,jpeg,png"
        ]);

        if ($request->hasFile("berkas")) {
            $file = $request->file('berkas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $berkas = "/storage/" . $file->storeAs('files', $fileName, 'public');
        } else {
            $request->validate([
                "url" => "required"
            ]);
            $berkas = $request->url;
        }

        Konsep::create([
            "name" => $request->name,
            "berkas" => $berkas,
            "caban_id" => null
        ]);

        return redirect()->route("konsep");
    }
}
