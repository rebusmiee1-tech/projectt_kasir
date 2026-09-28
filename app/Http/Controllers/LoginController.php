<?php

namespace App\Http\Controllers;

use App\Models\UserPos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $user = UserPos::where('username', $request->username)
            ->where('is_active', 1)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password))  {
            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        $token = $user->createToken('pos-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'id_role' => $user->id_role,
                'id_sekolah' => $user->id_sekolah,
            ]
        ]);
    }
}