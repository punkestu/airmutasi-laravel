<?php

namespace App\Http\Controllers\API\Rotasi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\CabangNode;
use App\Models\PersonelJabatanCategory;

class CabangController extends Controller
{
    public function searchPosisi(Request $request)
    {
        $search = $request->search;

        $positions = PersonelJabatanCategory::where("jabatan", "LIKE", "%$search%")->get();
        return response()->json($positions);
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $cabangs = Cabang::whereRaw('UPPER(nama) LIKE ?', ['%' . strtoupper($search) . '%'])->get();
        return response()->json($cabangs);
    }
    public function summary($id)
    {
        $cabang = Cabang::select('id', 'nama', 'alamat', 'thumbnail_url')->where('id', $id)->first();
        return response()->json($cabang);
    }
    public function inKelas($kelas)
    {
        $cabangs = Cabang::whereHas('kelases', function ($query) use ($kelas) {
            $query->where('kelas_id', $kelas);
        })->get();
        return response()->json($cabangs);
    }
    public function inSameKelas($id)
    {
        $cabang = Cabang::with('kelases')->find($id);
        if ($cabang && $cabang->kelases) {
            $cabangs = Cabang::whereHas('kelases', function ($query) use ($cabang) {
                $query->whereIn('kelas_id', $cabang->kelases->pluck('kelas_id'));
            })->get();
            return response()->json($cabangs);
        }
        return response()->json([]);
    }
    public function all()
    {
        $cabangs = Cabang::all();
        return response()->json($cabangs);
    }
    public function listInduk()
    {
        $cabang = Cabang::has("coord")->get()->map(function ($cabang) {
            return [
                'id' => $cabang->id,
                'nama' => $cabang->nama,
                'alamat' => $cabang->alamat,
                'thumbnail_url' => $cabang->thumbnail_url,
                'latitude' => $cabang->coord->latitude,
                'longitude' => $cabang->coord->longitude,
            ];
        });
        return response()->json($cabang);
    }
    public function tree()
    {
        $tree = CabangNode::getTree();
        return response()->json($tree);
    }
    public function editNode(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'root_id' => 'nullable|exists:cabang_nodes,id',
        ]);
        $node = CabangNode::where('cabang_id', $request->cabang_id)->first();
        if ($node && $node->id == $request->root_id) {
            return response()->json(['message' => 'success']);
        }
        CabangNode::updateOrCreate(
            ['cabang_id' => $request->cabang_id],
            ['cabang_id' => $request->cabang_id, 'root_id' => $request->root_id]
        );
        return response()->json(['message' => 'success']);
    }
    public function deleteNode($cabang_id)
    {
        CabangNode::where('cabang_id', $cabang_id)->delete();
        return response()->json(['message' => 'success']);
    }
}
