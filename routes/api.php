<?php

use Illuminate\Http\Request;
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
use App\Http\Controllers\LoginController;


Route::post('/login', [LoginController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logout berhasil'
    ]);
});

    Route::get('/barang', [BarangController::class, 'index']);

    Route::post('/barang', [BarangController::class, 'store'])
        ->middleware('role:1,2');

    Route::put('/barang/{id}', [BarangController::class, 'update'])
        ->middleware('role:1,2');

    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])
        ->middleware('role:1,2');

   Route::get('/kategori', [KategoriController::class, 'index']);

    Route::post('/kategori', [KategoriController::class, 'store'])
        ->middleware('role:1,2');

    Route::put('/kategori/{id}', [KategoriController::class, 'update'])
        ->middleware('role:1,2');

    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])
        ->middleware('role:1,2');

    Route::get('/kelompok-kategori', [KelompokKategoriController::class, 'index']);

    Route::post('/kelompok-kategori', [KelompokKategoriController::class, 'store'])
        ->middleware('role:1,2');

    Route::put('/kelompok-kategori/{id}', [KelompokKategoriController::class, 'update'])
        ->middleware('role:1,2');

    Route::delete('/kelompok-kategori/{id}', [KelompokKategoriController::class, 'destroy'])
        ->middleware('role:1,2');

    Route::get('/supplier', [SupplierController::class, 'index']);

    Route::post('/supplier', [SupplierController::class, 'store'])
        ->middleware('role:1,2');

    Route::put('/supplier/{id}', [SupplierController::class, 'update'])
        ->middleware('role:1,2');

    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])
        ->middleware('role:1,2');

    Route::get('/pelanggan', [PelangganController::class, 'index']);

    Route::post('/pelanggan', [PelangganController::class, 'store'])
        ->middleware('role:1,2,3');

    Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])
        ->middleware('role:1,2,3');

    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])
        ->middleware('role:1,2,3');

    Route::get('/user-pos', [UserPosController::class, 'index'])
    ->middleware('role:1');

    Route::get('/pembelian', [PembelianController::class, 'index'])
    ->middleware('role:1,2');

    Route::post('/pembelian', [PembelianController::class, 'store'])
        ->middleware('role:1,2');

    Route::put('/pembelian/{id}', [PembelianController::class, 'update'])
        ->middleware('role:1,2');

    Route::delete('/pembelian/{id}', [PembelianController::class, 'destroy'])
        ->middleware('role:1,2');

    Route::get('/detail-pembelian', [DetailPembelianController::class, 'index'])
    ->middleware('role:1,2');

    Route::post('/detail-pembelian', [DetailPembelianController::class, 'store'])
        ->middleware('role:1,2');

    Route::put('/detail-pembelian/{id}', [DetailPembelianController::class, 'update'])
        ->middleware('role:1,2');

    Route::delete('/detail-pembelian/{id}', [DetailPembelianController::class, 'destroy'])
        ->middleware('role:1,2');
    Route::get('/penjualan', [PenjualanController::class, 'index'])
    ->middleware('role:1,2,3');

    Route::post('/penjualan', [PenjualanController::class, 'store'])
        ->middleware('role:1,2,3');

    Route::put('/penjualan/{id}', [PenjualanController::class, 'update'])
        ->middleware('role:1,2,3');

    Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])
        ->middleware('role:1,2,3');

    Route::get('/detail-penjualan', [DetailPenjualanController::class, 'index'])
    ->middleware('role:1,2,3');

    Route::post('/detail-penjualan', [DetailPenjualanController::class, 'store'])
        ->middleware('role:1,2,3');

    Route::put('/detail-penjualan/{id}', [DetailPenjualanController::class, 'update'])
        ->middleware('role:1,2,3');

    Route::delete('/detail-penjualan/{id}', [DetailPenjualanController::class, 'destroy'])
        ->middleware('role:1,2,3');
});