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
}
