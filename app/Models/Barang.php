<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;
use App\Models\Supplier;

class Barang extends Model
{
    protected $table = 'tb_barang';

    protected $primaryKey = 'id_barang';

    public $timestamps = false;

    protected $fillable = [
        'id_sekolah',
        'barcode',
        'nama',
        'id_kategori',
        'id_kelompok_kategori',
        'id_supplier',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'is_active',
    ];

    public function kategori()
    {
    return $this->belongsTo(
        Kategori::class,
        'id_kategori',
        'id_kategori'
    );
    }

    public function supplier()
    {
    return $this->belongsTo(
        Supplier::class,
        'id_supplier',
        'id_supplier'
        );
    }
}