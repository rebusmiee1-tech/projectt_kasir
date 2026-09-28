<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $penjualanHariIni = Penjualan::whereDate(
            'tanggal_penjualan',
            $today
        )->sum('total_faktur');

        $jumlahTransaksi = Penjualan::count();

        $produkTerjual = DetailPenjualan::sum('jumlah_barang');

        $jumlahPelanggan = Pelanggan::count();

        $grafikPenjualan = Penjualan::select(
                DB::raw('DATE(tanggal_penjualan) as tanggal'),
                DB::raw('SUM(total_faktur) as total')
            )
            ->whereDate(
                'tanggal_penjualan',
                '>=',
                now()->subDays(6)->toDateString()
            )
            ->groupBy(DB::raw('DATE(tanggal_penjualan)'))
            ->orderBy('tanggal')
            ->get();

        $transaksiTerbaru = Penjualan::with([
                'pelanggan',
                'user',
            ])
            ->orderByDesc('tanggal_penjualan')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'statistik' => [
                'penjualanHariIni' => $penjualanHariIni,
                'jumlahTransaksi' => $jumlahTransaksi,
                'produkTerjual' => $produkTerjual,
                'jumlahPelanggan' => $jumlahPelanggan,
            ],
            'grafikPenjualan' => $grafikPenjualan,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }
}