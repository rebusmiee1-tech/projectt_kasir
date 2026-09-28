<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UserPos extends Model
{
    use HasApiTokens;

    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

protected $hidden = [
    'password',
    ];

    protected $fillable = [
    'id_sekolah',
    'id_role',
    'username',
    'password',
    'nama_lengkap',
    'is_active',
];
}
