<?php

namespace App\Http\Controllers\Api;

use App\Models\Tugas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\TugasResource;
use Illuminate\Contracts\Cache\Store;
use Illuminate\support\facades\Validator;
use Illuminate\support\facades\Storage;


class TugasController extends Controller
{

    // Get All Tugas
    public function index()
    {
        // Get All Tugas
        // $tugas = Tugas::latest()->paginate(5);
        $tugas = Tugas::latest();

        return new TugasResource(true, 'List Data Tugas', $tugas);
    }

    //Insert Tugas 
    public function store(Request $request)
    {

        // Buat Validasi
        $file = '';

        $validator = Validator::make($request->all(), [
            'nama_tugas' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Upload file
        if ($request->file('file')) {
            $file = $request->file('file');
            $file->storeAs('public/tugas', $file->hashName());
            $file = $file->hashName();
        }

        // Create tugas
        $tugas = Tugas::create([
            'nama_tugas' => $request->nama_tugas,
            'prioritas' => $request->prioritas,
            'deadline' => date('d-m-Y', strtotime($request->deadline)),
            'file' => $file,
            'tim' => $request->tim,
        ]);

        // return response
        return new TugasResource(true, 'Data Tugas Berhasil Ditambahkan', $tugas);
    }

    // Show Detail Tugas
    public function show($id)
    {
        $tugas = Tugas::find($id);

        return new TugasResource(true, 'Detail Data Tugas', $tugas);
    }

    // Update Tugas
    public function update(Request $request, $id)
    {
        // Buat Validasi
        $file = '';

        $validator = Validator::make($request->all(), [
            'nama_tugas' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tugas = Tugas::find($id);

        // Upload file
        if ($request->file('file')) {
            // Upload file baru
            $file = $request->file('file');
            $file->storeAs('public/tugas', $file->hashName());

            // Hapus file lama
            Storage::delete('public/tugas/' . basename($tugas->file));

            $tugas->update([
                'nama_tugas' => $request->nama_tugas,
                'prioritas' => $request->prioritas,
                'deadline' => date('d-m-Y', strtotime($request->deadline)),
                'file' => $file->hashName(),
                'tim' => $request->tim,

            ]);
        } else {
            $tugas->update([
                'nama_tugas' => $request->nama_tugas,
                'prioritas' => $request->prioritas,
                'deadline' => date('d-m-Y', strtotime($request->deadline)),
                'tim' => $request->tim,

            ]);
        }

        return new TugasResource(true, 'Data Tugas Berhasil Diubah', $tugas);
    }

    // Hapus Tugas
    public function destroy($id)
    {
        $tugas = Tugas::find($id);

        Storage::delete('public/tugas/' . basename($tugas->file));

        $tugas->delete();

        return new TugasResource(true, 'Data Tugas Berhasil Dihapus', null);
    }
}
