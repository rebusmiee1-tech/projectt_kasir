<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();

        return $barang;
    }

    public function store(Request $request)
    {
    $request->validate([
        'id_sekolah' => 'required|integer',
        'barcode' => 'nullable|string',
        'nama' => 'required|string',
        'id_kategori' => 'nullable|integer',
        'id_kelompok_kategori' => 'nullable|integer',
        'id_supplier' => 'nullable|integer',
        'satuan' => 'required|string',
        'harga_beli' => 'nullable|numeric',
        'harga_jual' => 'nullable|numeric',
        'stok' => 'nullable|integer|min:0',
        'is_active' => 'nullable|boolean',
    ]);

    $barang = Barang::create($request->all());

    return $barang;
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'id_sekolah' => 'required|integer',
        'barcode' => 'nullable|string',
        'nama' => 'required|string',
        'id_kategori' => 'nullable|integer',
        'id_kelompok_kategori' => 'nullable|integer',
        'id_supplier' => 'nullable|integer',
        'satuan' => 'required|string',
        'harga_beli' => 'nullable|numeric',
        'harga_jual' => 'nullable|numeric',
        'stok' => 'nullable|integer|min:0',
        'is_active' => 'nullable|boolean',
    ]);

    $barang = Barang::findOrFail($id);
    $barang->update($request->all());

    return $barang;
    }

    public function destroy($id)
    {
    $barang = Barang::findOrFail($id);

    $barang->delete();

    return response()->json([
        'message' => 'Barang berhasil dihapus'
    ]);
    }
}