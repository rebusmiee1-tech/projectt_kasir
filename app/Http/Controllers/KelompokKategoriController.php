<?php

namespace App\Http\Controllers;

use App\Models\KelompokKategori;
use Illuminate\Http\Request;

class KelompokKategoriController extends Controller
{
    public function index()
    {
        $kelompokKategori = KelompokKategori::all();

        return $kelompokKategori;
    }

    public function store(Request $request)
    {
    $request->validate([
        'id_sekolah' => 'required|integer',
        'nama_kelompok' => 'required|string',
    ]);

    $kelompokKategori = KelompokKategori::create($request->all());

    return $kelompokKategori;
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'id_sekolah' => 'required|integer',
        'nama_kelompok' => 'required|string',
    ]);

    $kelompokKategori = KelompokKategori::findOrFail($id);

    $kelompokKategori->update($request->all());

    return $kelompokKategori;
    }
    public function destroy($id)
    {
    $kelompokKategori = KelompokKategori::findOrFail($id);

    $kelompokKategori->delete();

    return response()->json([
        'message' => 'Kelompok kategori berhasil dihapus'
    ]);
    }
}