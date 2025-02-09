<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\PersonelJabatanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
        $akun = auth()->user();
        if ($akun->role->name == 'admin') {
            $akuns = Profile::with(['user'])->get();
            $cabangs = Cabang::all();
            $kategori_jabatan = PersonelJabatanCategory::select('category')->distinct()->get();
            $jabatans = PersonelJabatanCategory::all();
            $jabatans = $jabatans->groupBy('category');
            return view('account.index', ['akun' => $akun, 'akuns' => $akuns, 'cabangs' => $cabangs, 'kategori_jabatan' => $kategori_jabatan, 'jabatans' => $jabatans]);
        }
        return view('account.index', ['akun' => $akun]);
    }

    public function inputView()
    {
        $cabangs = Cabang::all();
        return view('account.input', ['cabangs' => $cabangs]);
    }

    public function input(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'nik' => 'required',
            'masa_kerja' => 'required',
            'jabatan' => 'required',
            'password' => 'required|min:8',
            'cabang_id' => 'required|exists:cabangs,id'
        ]);
        DB::beginTransaction();
        $akun = new User();
        $akun->name = $request->name;
        $akun->email = $request->email;
        $akun->password = bcrypt($request->password);
        $akun->role_id = Role::where('name', 'personel')->first()->id;
        $akun->save();
        if (!$akun->profile) {
            $akun->profile = new Profile();
            $akun->profile->user_id = $akun->id;
        }
        $akun->profile->nik = $request->nik;
        $akun->profile->masa_kerja = $request->masa_kerja;
        $akun->profile->jabatan = $request->jabatan;
        $akun->profile->cabang_id = $request->cabang_id;
        $akun->profile->save();
        DB::commit();
        return redirect()->route('akun')->with('success', 'Akun berhasil ditambahkan');
    }

    public function updateView()
    {
        $akun = auth()->user();
        return view('account.update', ['akun' => $akun]);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        $akun = User::find(Auth::user()->id);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $akun->id,
            'password' => 'nullable|min:8',
            'nik' => 'required',
            'masa_kerja' => 'required',
            'jabatan' => 'required',
        ]);
        $email_changed = false;
        if ($akun->email != $request->email) {
            $email_changed = true;
        }
        $password_changed = false;
        if (bcrypt($request->password) != $akun->password) {
            $password_changed = true;
        }

        if ($email_changed || $password_changed) {
            UserNotification::create([
                'user_id' => $akun->id,
                'description' => ($email_changed ? 'Email' : '') . ($email_changed && $password_changed ? ' dan ' : '') . ($password_changed ? 'Password' : '') . ' telah diubah',
                'is_read' => false
            ]);
        }

        $akun->name = $request->name;
        $akun->email = $request->email;
        $akun->save();
        if (!$akun->profile) {
            $akun->profile = new Profile();
            $akun->profile->user_id = $akun->id;
        }
        $akun->profile->user_id = $akun->id;
        $akun->profile->nik = $request->nik;
        $akun->profile->masa_kerja = $request->masa_kerja;
        $akun->profile->jabatan = $request->jabatan;
        if ($request->password) {
            $akun->password = bcrypt($request->password);
        }
        $akun->profile->save();
        DB::commit();
        return redirect()->route('akun')->with('success', 'Akun berhasil diubah');
    }

    public function assign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:profiles,id',
            'cabang_id' => 'required|exists:cabangs,id'
        ]);
        $profile = Profile::find($request->user_id);
        $profile->cabang_id = $request->cabang_id;
        $profile->save();
        return redirect()->route('akun')->with('success', 'Cabang berhasil diassign');
    }
}
