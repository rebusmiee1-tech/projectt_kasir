<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;
use App\Models\UserPos;

class Penjualan extends Model
{
    protected $table = 'tb_penjualan';

    protected $primaryKey = 'id_penjualan';

    public $timestamps = false;

    protected $fillable = [
        'id_sekolah',
        'id_user',
        'id_pelanggan',
        'tanggal_penjualan',
        'total_faktur',
        'total_bayar',
        'kembalian',
        'status_pembayaran',
        'jenis_transaksi',
        'cara_bayar',
        'note',
    ];

    public function pelanggan()
    {
    return $this->belongsTo(
        Pelanggan::class,
        'id_pelanggan',
        'id_pelanggan'
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