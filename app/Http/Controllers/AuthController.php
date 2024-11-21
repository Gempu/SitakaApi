<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    /**
     * Menampilkan profil pengguna
     */
    public function showProfile(Request $request)
    {
        // Dapatkan data pengguna yang sedang login
        $user = $request->user();

        // Buku yang sedang dipinjam (1 buku)
        $currentLoan = Loan::with(['item.biblio'])
            ->where('member_id', $user->member_id)
            ->whereNull('return_date') // Buku yang belum dikembalikan
            ->first();

        $currentBook = $currentLoan ? [
            'title' => $currentLoan->item->biblio->title ?? 'Unknown',
            'cover' => $currentLoan->item->biblio->cover,
            'rating' => $currentLoan->item->biblio->ratings->avg('rating_value') ?? 'No rating',
            'borrow_date' => $currentLoan->loan_date,
            'due_date' => $currentLoan->due_date,
        ] : null;

        // Riwayat buku yang telah dipinjam
        $loanHistory = Loan::with(['item.biblio'])
            ->where('member_id', $user->member_id)
            ->whereNotNull('return_date') // Buku yang sudah dikembalikan
            ->orderByDesc('return_date')
            ->get()
            ->map(function ($loan) {
                return [
                    'title' => $loan->item->biblio->title ?? 'Unknown',
                    'borrow_date' => $loan->loan_date,
                    'return_date' => $loan->return_date,
                ];
            });

        // Format data profil untuk respons
        $profileData = [
            'member_name' => $user->member_name,
            'member_id' => $user->member_id,
            'current_book' => $currentBook,
            'loan_history' => $loanHistory,
        ];

        return response()->json([
            'code' => '200',
            'status' => 'OK',
            'data' => $profileData,
        ], 200);
    }

    public function getNotifications(Request $request)
    {
        $user = $request->user();

        $notifications = $user->notifications()->orderByDesc('created_at')->get();

        return response()->json([
            'code' => '200',
            'status' => 'OK',
            'data' => $notifications,
        ]);
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
