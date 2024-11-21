<?php

namespace App\Http\Controllers;

use App\Models\Biblio;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function getBooks()
    {
        $books = Biblio::with('ratings') // Pastikan relasi 'ratings' dimuat
            ->orderByDesc('input_date')
            ->take(10)
            ->get()
            ->map(function ($biblio) {
                return [
                    'title' => $biblio->title,
                    'authors' => $biblio->sor,
                    'publish_year' => $biblio->publish_year,
                    'code' => $biblio->call_number,
                    'cover' => $biblio->cover,
                    'rating' => $biblio->ratings->avg('rating_value') ?? 'No rating', // Rata-rata rating
                ];
            });


        return response()->json([
            "code" => "200",
            "status" => "OK",
            "data" => [
                "books" => $books,
            ]
        ],200);

        // $books = Biblio::with(['authors', 'rating'])
        //     ->orderByDesc('rating.rating_value')
        //     ->take(10)
        //     ->get()
        //     ->map(function ($biblio) {
        //         return [
        //             'title' => $biblio->title,
        //             'authors' => $biblio->authors->pluck('author_name')->implode(', '),
        //             'publish_year' => $biblio->publish_year,
        //             'code' => $biblio->call_number,
        //             'rating' => optional($biblio->rating)->rating_value ?? 'No rating', // Nilai rating
        //         ];
        //     });

        // return response()->json($books);
    }

    public function bookDetail($biblio_id)
    {
        $biblio = Biblio::where('biblio_id', $biblio_id)
            ->first();

        if (!$biblio) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $data = [
            'title' => $biblio->title,
            'authors' => $biblio->sor,
            'publish_year' => $biblio->publish_year,
            'pages' => $biblio->collation,
            'synopsis' => $biblio->notes,
            'cover' => $biblio->cover,
            'rating' => $biblio->ratings->avg('rating_value') ?? 'No rating',
            'available_copies' => $biblio->items->where('item_status_id', '==', '0')->count(),
            'availability' => $biblio->items
                ->map(function ($item) {
                    return [
                        'shelf_location' => $item->site,
                        'item_code' => $item->item_code,
                        'item_status' => $item->item_status_id,
                    ];
                })
        ];

        return response()->json($data);
    }
}
