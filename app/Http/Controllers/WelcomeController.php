<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Personel;
use App\Models\WelcomePopup;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $personelCount = Personel::groupBy('type')->selectRaw('count(*) as total, type')->pluck('total', 'type');
        $personelOperasi = ($personelCount['Operasi'] ?? 0) + ($personelCount[""] ?? 0);
        $personelTeknik = $personelCount['Teknik'] ?? 0;
        $personelUmum = $personelCount['Umum'] ?? 0;
        $cabangs = Cabang::select('thumbnail_url')->get();
        if (session()->has("justlogin")) {
            $welcomePopup = WelcomePopup::latest('created_at')->first();
            return view('welcome', ["personelOperasi" => $personelOperasi, "personelTeknik" => $personelTeknik, "personelUmum" => $personelUmum, "cabangs" => $cabangs, "welcomepopup" => $welcomePopup]);
        }
        return view('welcome', ["personelOperasi" => $personelOperasi, "personelTeknik" => $personelTeknik, "personelUmum" => $personelUmum, "cabangs" => $cabangs]);
    }
    public function updateWelcomePopup(Request $request)
    {
        $request->validate([
            "welcome-popup" => "required|image|max:2048"
        ]);
        $path = $request->file("welcome-popup")->store("welcome-popup", "public");
        WelcomePopup::create(["path" => $path]);
        return redirect()->back()->with("success", "Berhasil mengubah welcome popup");
    }
}
