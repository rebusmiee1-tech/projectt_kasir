<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Http\Controllers\PenjualanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPenjualanController extends Controller
{
    public function index()
    {
        $detailPenjualan = DetailPenjualan::all();

        return $detailPenjualan;
    }

    public function store(Request $request)
{
    $request->validate([
        'id_penjualan' => 'required|integer',
        'id_barang' => 'required|integer',
        'jumlah_barang' => 'required|integer|min:1',
        'harga_beli' => 'required|numeric|min:0',
        'harga_jual' => 'required|numeric|min:0',
        'diskon_tipe' => 'nullable|in:persen,nominal',
        'diskon_nilai' => 'nullable|numeric|min:0',
    ]);

    return DB::transaction(function () use ($request) {

        $barang = Barang::findOrFail($request->id_barang);

        if ($barang->stok < $request->jumlah_barang) {
            return response()->json([
                'message' => 'Stok barang tidak mencukupi'
            ], 422);
        }

        // Hitung total harga sebelum diskon
        $totalHarga = $request->jumlah_barang * $request->harga_jual;

        // Hitung diskon
        $diskonNominal = 0;

        if ($request->diskon_tipe === 'persen') {
            $diskonNominal = $totalHarga * (($request->diskon_nilai ?? 0) / 100);
        } elseif ($request->diskon_tipe === 'nominal') {
            $diskonNominal = $request->diskon_nilai ?? 0;
        }

        // Pastikan diskon tidak melebihi total harga
        if ($diskonNominal > $totalHarga) {
            $diskonNominal = $totalHarga;
        }

        // Hitung subtotal setelah diskon
        $subtotal = $totalHarga - $diskonNominal;

        $detailPenjualan = DetailPenjualan::create([
            'id_penjualan' => $request->id_penjualan,
            'id_barang' => $request->id_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'diskon_tipe' => $request->diskon_tipe,
            'diskon_nilai' => $request->diskon_nilai ?? 0,
            'diskon_nominal' => $diskonNominal,
            'subtotal' => $subtotal,
        ]);

        // Kurangi stok
        $barang->stok -= $request->jumlah_barang;
        $barang->save();

        // Update total faktur penjualan
        $penjualanController = app(PenjualanController::class);
        $penjualanController->updateTotalFaktur($request->id_penjualan);

        return $detailPenjualan;
    });
}

    public function update(Request $request, $id)
{
    $request->validate([
        'id_penjualan' => 'required|integer',
        'id_barang' => 'required|integer',
        'jumlah_barang' => 'required|integer|min:1',
        'harga_beli' => 'required|numeric|min:0',
        'harga_jual' => 'required|numeric|min:0',
        'diskon_tipe' => 'nullable|in:persen,nominal',
        'diskon_nilai' => 'nullable|numeric|min:0',
    ]);

    return DB::transaction(function () use ($request, $id) {

        $detailPenjualan = DetailPenjualan::findOrFail($id);

        $barangLama = Barang::findOrFail(
            $detailPenjualan->id_barang
        );

        $barangBaru = Barang::findOrFail(
            $request->id_barang
        );

        $jumlahLama = $detailPenjualan->jumlah_barang;
        $jumlahBaru = $request->jumlah_barang;

        // Jika barang tidak diganti
        if ($barangLama->id_barang == $barangBaru->id_barang) {

            $selisih = $jumlahBaru - $jumlahLama;

            // Jumlah bertambah
            if ($selisih > 0) {
                if ($barangBaru->stok < $selisih) {
                    return response()->json([
                        'message' => 'Stok barang tidak mencukupi'
                    ], 422);
                }

                $barangBaru->stok -= $selisih;
            }

            // Jumlah berkurang
            if ($selisih < 0) {
                $barangBaru->stok += abs($selisih);
            }

            $barangBaru->save();
        }

        // Jika barang diganti
        else {

            // Kembalikan stok barang lama
            $barangLama->stok += $jumlahLama;
            $barangLama->save();

            // Cek stok barang baru
            if ($barangBaru->stok < $jumlahBaru) {
                return response()->json([
                    'message' => 'Stok barang baru tidak mencukupi'
                ], 422);
            }

            // Kurangi stok barang baru
            $barangBaru->stok -= $jumlahBaru;
            $barangBaru->save();
        }

        // Hitung total harga sebelum diskon
        $totalHarga = $jumlahBaru * $request->harga_jual;

        // Hitung diskon
        $diskonNominal = 0;

        if ($request->diskon_tipe === 'persen') {
            $diskonNominal =
                $totalHarga *
                (($request->diskon_nilai ?? 0) / 100);
        } elseif ($request->diskon_tipe === 'nominal') {
            $diskonNominal = $request->diskon_nilai ?? 0;
        }

        // Pastikan diskon tidak melebihi total harga
        if ($diskonNominal > $totalHarga) {
            $diskonNominal = $totalHarga;
        }

        // Hitung subtotal
        $subtotal = $totalHarga - $diskonNominal;

        // Update detail
        $detailPenjualan->update([
            'id_penjualan' => $request->id_penjualan,
            'id_barang' => $request->id_barang,
            'jumlah_barang' => $jumlahBaru,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'diskon_tipe' => $request->diskon_tipe,
            'diskon_nilai' => $request->diskon_nilai ?? 0,
            'diskon_nominal' => $diskonNominal,
            'subtotal' => $subtotal,
        ]);

        // Update total faktur
        $penjualanController = app(PenjualanController::class);

        $penjualanController->updateTotalFaktur(
            $request->id_penjualan
        );

        return $detailPenjualan;
    });
}
    public function destroy($id)
    {
        $detailPenjualan = DetailPenjualan::findOrFail($id);

        $barang = Barang::findOrFail($detailPenjualan->id_barang);

        $barang->stok += $detailPenjualan->jumlah_barang;

        $barang->save();

        $detailPenjualan->delete();

        $penjualanController = app(PenjualanController::class);
        $penjualanController->updateTotalFaktur($detailPenjualan->id_penjualan);

        return response()->json([
            'message' => 'Detail penjualan berhasil dihapus'
        ]);
    }
}