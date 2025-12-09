<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    protected $perPage = 3;

    // Показываем страницу с первыми 3 отзывами
    public function index()
    {
        $reviews = Review::latest()->take($this->perPage)->get();
        $total = Review::count();

        return view('reviews.index', [
            'reviews' => $reviews,
            'loaded' => $reviews->count(),
            'total' => $total,
            'perPage' => $this->perPage,
        ]);
    }

    // Сохранение нового отзыва (AJAX)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create($data);

        // Можно вернуть html-фрагмент или json
        return response()->json([
            'success' => true,
            'review' => $review,
            'rendered' => view('reviews._review', ['review' => $review])->render(),
        ]);
    }

    // Загрузка следующих отзывов через AJAX
    public function loadMore(Request $request)
    {
        $offset = (int)$request->input('offset', 0);
        $perPage = $this->perPage;

        $reviews = Review::latest()
            ->skip($offset)
            ->take($perPage)
            ->get();

        $rendered = '';
        foreach ($reviews as $review) {
            $rendered .= view('reviews._review', ['review' => $review])->render();
        }

        // вернуть количество загруженных и HTML
        return response()->json([
            'success' => true,
            'count' => $reviews->count(),
            'html' => $rendered,
        ]);
    }
}
