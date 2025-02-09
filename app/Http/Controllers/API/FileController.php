<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function uploadDoc(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'file' => 'file|max:2048'
        ], [
            'file.file' => 'Berkas harus berupa file',
            'file.max' => 'Ukuran berkas terlalu besar (maks 2MB)',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->messages()->first()
            ], 400);
        }
        if (!$request->hasFile('file')) {
            return response()->json([
                'message' => 'File kosong'
            ], 100);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('files', $fileName, 'public');

        return response()->json([
            'message' => 'File uploaded successfully',
            'url' => asset('storage/files/' . $fileName)
        ]);
    }
    public function deleteDoc(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('files', $fileName, 'public');

        return response()->json([
            'message' => 'File uploaded successfully',
            'url' => asset('storage/files/' . $fileName)
        ]);
    }
}
