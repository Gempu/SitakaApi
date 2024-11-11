<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RankingController extends Controller
{
    // 1. Mendapatkan top 10 member dengan skor tertinggi
    public function index(Request $request)
    {
        $topMembers = Member::orderByDesc('score')
            ->take(10)
            ->get()
            ->map(function ($member, $index) {
                return [
                    'rank' => $member->score != 0 ? $index + 1 : '-',
                    'member_id' => $member->member_id,
                    'member_name' => $member->member_name,
                    'score' => $member->score,
                ];
            });

        $user = $request->user();

        $user_rank = Member::where('score', '>', $user->score)->count() + 1;

        $data_user_rank = [
            'rank' => $user->score != 0 ? $user_rank : '-',
            'member_id' => $user->member_id,
            'member_name' => $user->member_name,
            'score' => $user->score,
        ];

        return response()->json([
            "code" => "200",
            "status" => "OK",
            "data" => [
                "top_members" => $topMembers,
                "user_rank" => $data_user_rank,
            ]
        ],200);
    }
}
