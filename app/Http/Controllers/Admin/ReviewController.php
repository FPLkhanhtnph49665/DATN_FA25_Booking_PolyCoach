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
    public function index()
    {
        $reviews = Review::with(['trip', 'user'])
            ->latest()
            ->paginate(25);

        return view('admin.reviews.index', compact('reviews'));
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
            'user_id'       => 'required|exists:users,id',
            'trip_id'       => 'required|exists:trips,id',
            'content'       => 'required|string|max:1000',
            'rating'        => 'required|integer|min:1|max:5',
            'status'        => 'required|in:pending,approved,rejected',
        ], [
            'content.required' => 'Please enter review content.',
            'rating.required'  => 'Please select a rating.',
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
            'user_id'       => 'required|exists:users,id',
            'trip_id'       => 'required|exists:trips,id',
            'content'       => 'required|string|max:1000',
            'rating'        => 'required|integer|min:1|max:5',
            'status'        => 'required|in:pending,approved,rejected',
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
