<?php 
 
namespace App\Http\Controllers; 
 
use App\Models\Kategori; 
use Illuminate\Http\Request; 
 
class KategoriController extends Controller 
{ 
    public function index() 
    { 
        return Kategori::all(); 
    } 
 
    public function store(Request $request)
    {
    $request->validate([
        'id_kelompok' => 'required|integer',
        'nama' => 'required|string',
    ]);

    $kategori = Kategori::create($request->all());

    return $kategori;
    }
 
    public function update(Request $request, $id)
    {
    $request->validate([
        'id_kelompok' => 'required|integer',
        'nama' => 'required|string',
    ]);

    $kategori = Kategori::findOrFail($id);

    $kategori->update($request->all());

    return $kategori;
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}