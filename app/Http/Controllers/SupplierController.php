<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $supplier = Supplier::all();

        return $supplier;
    }

    public function store(Request $request)
{
    $request->validate([
        'id_sekolah' => 'required|integer',
        'nama' => 'required|string',
        'no_telepon' => 'nullable|string',
        'alamat_supplier' => 'nullable|string',
    ]);

    $supplier = Supplier::create($request->all());

    return $supplier;
}
    public function update(Request $request, $id)
{
    $request->validate([
        'id_sekolah' => 'required|integer',
        'nama' => 'required|string',
        'no_telepon' => 'nullable|string',
        'alamat_supplier' => 'nullable|string',
    ]);

    $supplier = Supplier::findOrFail($id);

    $supplier->update($request->all());

    return $supplier;
}

    public function destroy($id)
    {
    $supplier = Supplier::findOrFail($id);

    $supplier->delete();

    return response()->json([
        'message' => 'Supplier berhasil dihapus'
    ]);
    }
}