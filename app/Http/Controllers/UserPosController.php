<?php

namespace App\Http\Controllers;

use App\Models\UserPos;

class UserPosController extends Controller
{
    public function index()
    {
        $users = UserPos::select(
            'id_user',
            'id_sekolah',
            'id_role',
            'username',
            'nama_lengkap',
            'is_active',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by'
        )->get();

        return $users;
    }
}