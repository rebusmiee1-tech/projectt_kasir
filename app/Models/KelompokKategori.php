<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class KelompokKategori extends Model
{
    protected $table = 'tb_kelompok_kategori';

    protected $primaryKey = 'id_kelompok';

    public $timestamps = false;

    protected $fillable = [
        'id_sekolah',
        'nama_kelompok',
    ];

    public function kategori()
    {
    return $this->hasMany(
        Kategori::class,
        'id_kelompok',
        'id_kelompok'
    );
    }
}