<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();

        return $pelanggan;
    }

    public function store(Request $request)
    {
        $pelanggan = Pelanggan::create($request->all());

        return $pelanggan;
    }

    public function update(Request $request, $id)
    {
    $pelanggan = Pelanggan::findOrFail($id);

    $pelanggan->update($request->all());

    return $pelanggan;
    }

    public function destroy($id)
    {
    $pelanggan = Pelanggan::findOrFail($id);

    $pelanggan->delete();

    return response()->json([
        'message' => 'Pelanggan berhasil dihapus'
    ]);
    }
}