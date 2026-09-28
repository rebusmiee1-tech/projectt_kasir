<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelompokKategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\UserPosController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\DetailPembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\DashboardController;

Route::inertia('/', 'auth/Login')->name('home');

Route::get('/barang', [BarangController::class, 'index']);
Route::post('/barang', [BarangController::class, 'store']);
Route::put('/barang/{id}', [BarangController::class, 'update']);
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/kelompok-kategori', [KelompokKategoriController::class, 'index']);
Route::get('/supplier', [SupplierController::class, 'index']);
Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::get('/user-pos', [UserPosController::class, 'index']);
Route::get('/detail-pembelian', [DetailPembelianController::class, 'index']);
Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::get('/detail-penjualan', [DetailPenjualanController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::inertia('/kasir', 'Kasir')->name('kasir');

Route::inertia('/barang', 'Produk')->name('produk');

Route::inertia('/pembelian', 'Pembelian')->name('pembelian');
