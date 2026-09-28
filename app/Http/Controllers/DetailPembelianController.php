<?php 
 
namespace App\Http\Controllers; 
 
use App\Models\DetailPembelian; 
use App\Models\Barang; 
use Illuminate\Http\Request; 
 
class DetailPembelianController extends Controller 
{ 
    public function index() 
    { 
        $detailPembelian = DetailPembelian::all(); 
 
        return $detailPembelian; 
    } 
 
    public function store(Request $request) 
    { 
        $request->validate([ 
            'id_pembelian' => 'required|integer', 
            'id_barang' => 'required|integer', 
            'satuan' => 'required|string', 
            'jumlah' => 'required|integer|min:1', 
            'harga_beli' => 'required|numeric|min:0', 
        ]); 
 
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
        $request->validate([
            'id_pembelian' => 'required|integer',
            'id_barang' => 'required|integer',
            'satuan' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
        ]);

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
 
        $barang = Barang::findOrFail($detailPembelian->id_barang); 
 
        $barang->stok -= $detailPembelian->jumlah; 
 
        $barang->save(); 
 
        $detailPembelian->delete(); 
 
        return response()->json([ 
            'message' => 'Detail pembelian berhasil dihapus' 
        ]); 
    } 
}