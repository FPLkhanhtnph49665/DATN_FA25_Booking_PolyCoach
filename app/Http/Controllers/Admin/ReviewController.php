<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        // 1. Eager Loading để tránh N+1 và tối ưu hiệu năng
        $query = Review::with(['user', 'trip.route.fromCity', 'trip.route.toCity']);

        // 2. Xử lý bộ lọc tìm kiếm
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                // Tìm trong nội dung đánh giá
                $q->where('content', 'LIKE', "%{$search}%")

                    // Tìm theo tên khách hàng
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('full_name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    })

                    // Tìm theo tên thành phố (Điểm đi hoặc Điểm đến)
                    ->orWhereHas('trip.route', function ($r) use ($search) {
                        $r->whereHas('fromCity', function ($c) use ($search) {
                            $c->where('name', 'LIKE', "%{$search}%");
                        })
                            ->orWhereHas('toCity', function ($c) use ($search) {
                                $c->where('name', 'LIKE', "%{$search}%");
                            });
                    });
            });
        }

        // 3. Lọc theo số sao
        if ($request->filled('stars')) {
            $query->where('rating', $request->input('stars'));
        }

        // 4. Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 5. Phân trang và giữ tham số lọc
        $reviews = $query->latest()->paginate(25)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    // cập nhật trạng thái
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review = Review::findOrFail($id);
        $review->status = $request->status;
        $review->save();

        return back()->with('success', 'Cập nhật trạng thái đánh giá thành công!');
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        $trips = Trip::all();
        $users = User::all();

        return view('admin.reviews.create', compact('trips', 'users'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:pending,approved,rejected',
        ], [
            'content.required' => 'Please enter review content.',
            'rating.required' => 'Please select a rating.',
        ]);

        Review::create($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully!');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['trip', 'user']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Review $review)
    {
        $trips = Trip::all();
        $users = User::all();
        return view('admin.reviews.edit', compact('review', 'trips', 'users'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully!');
    }

    /**
     * Soft delete the review.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully!');
    }

    /**
     * Display soft-deleted reviews (trash).
     */
    public function trash()
    {
        $reviews = Review::onlyTrashed()->with(['trip', 'user'])->paginate(25);
        return view('admin.reviews.trash', compact('reviews'));
    }

    /**
     * Restore a soft-deleted review.
     */
    public function restore($id): RedirectResponse
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->restore();
        return redirect()->route('admin.reviews.index')->with('success', 'Review restored successfully!');
    }

    /**
     * Permanently delete a review.
     */
    public function forceDelete($id): RedirectResponse
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->forceDelete();
        return redirect()->route('admin.reviews.trash')->with('success', 'Review permanently deleted!');
    }
}
