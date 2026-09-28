<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::all();

        return $penjualan;
    }

    public function store(Request $request)
{
    $request->validate([
        'total_bayar' => 'required|numeric|min:0',
    ]);

    $penjualan = Penjualan::create([
        'id_user' => $request->user()->id_user,
        'id_sekolah' => $request->user()->id_sekolah,
        'tanggal_penjualan' => now(),
        'total_bayar' => $request->total_bayar,
        'total_faktur' => 0,
        'kembalian' => 0,
        'status_pembayaran' => 'sudah bayar',
        'jenis_transaksi' => 'tunai',
        'cara_bayar' => 'tunai',
    ]);

    return $penjualan;
}

    public function updateTotalFaktur($idPenjualan)
{
    $totalFaktur = DetailPenjualan::where(
        'id_penjualan',
        $idPenjualan
    )->sum('subtotal');

    $penjualan = Penjualan::findOrFail($idPenjualan);

    $penjualan->total_faktur = $totalFaktur;
    $penjualan->kembalian = $penjualan->total_bayar - $totalFaktur;

    $penjualan->save();
}
    public function update(Request $request, $id)
    {
    $penjualan = Penjualan::findOrFail($id);
    $penjualan->update($request->all());

    return $penjualan;
    }

    public function destroy($id)
    {
    $penjualan = Penjualan::findOrFail($id);
    $penjualan->delete();

    return response()->json([
        'message' => 'Penjualan berhasil dihapus'
    ]);
    }
}