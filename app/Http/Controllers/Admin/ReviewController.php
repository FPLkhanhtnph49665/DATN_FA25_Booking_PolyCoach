<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá.
     */
    public function index()
    {
        $reviews = Review::with(['trip', 'user'])
            ->latest()
            ->paginate(25);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Hiển thị form tạo mới đánh giá.
     */
    public function create()
    {
        $trips = Trip::all();
        $users = User::all();
        return view('admin.reviews.create', compact('trips', 'users'));
    }

    /**
     * Lưu đánh giá mới vào database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'trip_id'        => 'required|exists:trips,id',
            'noi_dung'       => 'required|string|max:1000',
            'diem_danh_gia'  => 'required|integer|min:1|max:5',
            'trang_thai'     => 'required|in:0,1',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá',
            'diem_danh_gia.required' => 'Vui lòng chọn điểm đánh giá',
        ]);

        Review::create($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Thêm đánh giá thành công!');
    }

    /**
     * Xem chi tiết một đánh giá.
     */
    public function show(Review $review)
    {
        $review->load(['trip', 'user']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Hiển thị form sửa đánh giá.
     */
    public function edit(Review $review)
    {
        $trips = Trip::all();
        $users = User::all();
        return view('admin.reviews.edit', compact('review', 'trips', 'users'));
    }

    /**
     * Cập nhật thông tin đánh giá.
     */
    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'trip_id'        => 'required|exists:trips,id',
            'noi_dung'       => 'required|string|max:1000',
            'diem_danh_gia'  => 'required|integer|min:1|max:5',
            'trang_thai'     => 'required|in:0,1',
        ]);

        $review->update($data);

        return redirect()->route('admin.reviews.index')->with('success', 'Cập nhật đánh giá thành công!');
    }

    /**
     * Xóa mềm đánh giá.
     */
    public function destroy(Review $review)
    {
    }
}
