<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\Pembelian;
use Illuminate\Http\Request;
class PembelianController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::with([
            'supplier',
            'user',
        ])->get();

        return $pembelian;
    }

    public function store(Request $request)
{
    $subtotal = $request->jumlah * $request->harga_beli;

    $detailPembelian = DetailPembelian::create([
        'id_pembelian' => $request->id_pembelian,
        'id_barang' => $request->id_barang,
        'satuan' => $request->satuan,
        'jumlah' => $request->jumlah,
        'harga_beli' => $request->harga_beli,
        'subtotal' => $subtotal,
    ]);

    $barang = Barang::findOrFail($request->id_barang);

    $barang->stok += $request->jumlah;

    $barang->save();
    
    return $detailPembelian;
}
   

    public function update(Request $request, $id)
{
    $detailPembelian = DetailPembelian::findOrFail($id);

    $stokLama = $detailPembelian->jumlah;

    $detailPembelian->update([
        'id_pembelian' => $request->id_pembelian,
        'id_barang' => $request->id_barang,
        'satuan' => $request->satuan,
        'jumlah' => $request->jumlah,
        'harga_beli' => $request->harga_beli,
        'subtotal' => $request->jumlah * $request->harga_beli,
    ]);

    $barang = Barang::findOrFail($request->id_barang);

    $selisih = $request->jumlah - $stokLama;

    $barang->stok += $selisih;

    $barang->save();

    return $detailPembelian;
}

    public function destroy($id)
    {
    $detailPembelian = DetailPembelian::findOrFail($id);

    $detailPembelian->delete();

    return response()->json([
        'message' => 'Detail pembelian berhasil dihapus'
    ]);
    }
}