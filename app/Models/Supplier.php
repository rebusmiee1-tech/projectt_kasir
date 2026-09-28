<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class Supplier extends Model
{
    protected $table = 'tb_supplier';

    protected $primaryKey = 'id_supplier';

    public $timestamps = false;

    protected $fillable = [
        'id_sekolah',
        'nama',
        'no_telepon',
        'alamat_supplier',
    ];

    public function barang()
    {
    return $this->hasMany(
        Barang::class,
        'id_supplier',
        'id_supplier'
    );
    }
}