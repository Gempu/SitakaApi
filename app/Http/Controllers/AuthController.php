<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $member = Member::where('member_email', $request->email)->first();

        if (!$member || $member->mpasswd == null || !Hash::check($request->password, $member->mpasswd)) {
            return response()->json([
                "code" => "401",
                "status" => "UNAUTHORIZED",
                "errors" => [
                    "message" => "Username or Password Wrong"
                ]
            ],401);
        }

        $token = $member->createToken('auth_token')->plainTextToken;

        return response()->json([
            "code" => "200",
            "status" => "OK",
            "data" => [
                "member" => $member->member_name,
                "token" => $token,
            ]
        ],200);
    }

    public function changePassword(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        // Dapatkan pengguna yang sedang login
        $user = $request->user();

        // Cek apakah password lama sesuai
        if (!Hash::check($validated['current_password'], $user->mpasswd)) {
            return response()->json([
                "code" => "400",
                "status" => "Error",
                "data" => [
                    "message" => "Current password is incorrect"
                ]
            ], 400);
        }

        // Update password
        $user->mpasswd = Hash::make($validated['new_password']);
        $user->save();

        // Berikan respon jika berhasil
        return response()->json([
            "code" => "200",
            "status" => "OK",
            "data" => [
                "message" => "Password changed successfully"
            ]
        ], 200);
    }
}
