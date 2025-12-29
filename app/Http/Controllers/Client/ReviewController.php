<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required',
            'route_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string',
        ]);

        Review::create([
            'user_id' => auth()->id(), // Lấy ID người dùng hiện tại
            'trip_id' => $request->trip_id,
            'route_id' => $request->route_id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'content' => $request->content,
            'status' => 'pending', // Mặc định chờ duyệt như trong schema của bạn
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá chuyến đi');
    }
}