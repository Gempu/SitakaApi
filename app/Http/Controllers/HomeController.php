<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Content;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getHome(Request $request)
    {
        $loans = $request->user()->loans()
            ->whereNull('return_date')
            ->get()
            ->map(function ($loan) {
                $dueDate = Carbon::parse($loan->due_date);
                $status = $dueDate->lessThan(now()) ? 'overdue' : 'on time';
                $remainingDays = $dueDate->diffInDays(now());

                return [
                    'title' => $loan->item->biblio->title,
                    'author' => $loan->item->biblio->sor,
                    'publish_year' => $loan->item->biblio->publish_year,
                    'cover' => $loan->item->biblio->cover,
                    'borrowed_date' => $loan->loan_date,
                    'due_date' => $loan->due_date,
                    'status' => $status,
                    'remaining_days' => $remainingDays,
                    'fine_amount' => $status === 'overdue' ? $remainingDays * 500 : 0
                ];
            });

        $news = Content::where('is_news', 1)
                       ->orderBy('input_date', 'desc')
                       ->take(5)
                       ->get(['content_title', 'content_desc'])
                       ->map(function ($news) {
                           return [
                               'title' => $news->content_id,
                               'title' => $news->content_title,
                               'summary' => substr($news->content_desc, 0, 100) . '...',
                           ];
                       });
        
        return response()->json([
            "code" => "200",
            "status" => "OK",
            "data" => [
                "loans" => $loans,
                "news" => $news,
            ]
        ],200);
    }

}
