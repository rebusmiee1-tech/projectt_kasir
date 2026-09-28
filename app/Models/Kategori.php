<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KelompokKategori;
use App\Models\Barang;

class Kategori extends Model
{
    protected $table = 'tb_kategori';

    protected $primaryKey = 'id_kategori';

    public $timestamps = false;

    protected $fillable = [
        'id_kelompok',
        'nama',
    ];

    public function kelompok()
    {
        return $this->belongsTo(
            KelompokKategori::class,
            'id_kelompok',
            'id_kelompok'
        );
    }

    public function barang()
    {
    return $this->hasMany(
        Barang::class,
        'id_kategori',
        'id_kategori'
    );
    }
}