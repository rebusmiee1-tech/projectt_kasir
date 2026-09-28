<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\UserPos;

class Pembelian extends Model
{
    protected $table = 'tb_pembelian';

    protected $primaryKey = 'id_pembelian';

    public $timestamps = false;

    protected $fillable = [
        'id_sekolah',
        'id_supplier',
        'id_user',
        'nomor_faktur',
        'tanggal_faktur',
        'total_bayar',
        'status_pembelian',
        'jenis_transaksi',
        'cara_bayar',
        'note',
    ];

    public function supplier()
    {
    return $this->belongsTo(
        Supplier::class,
        'id_supplier',
        'id_supplier'
    );
    }

    public function user()
    {
    return $this->belongsTo(
        UserPos::class,
        'id_user',
        'id_user'
    );
    }
}