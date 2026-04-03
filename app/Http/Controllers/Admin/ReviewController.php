<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of product reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->orderBy('id', 'desc');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $reviews = $query->paginate(10);
        
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Update the approval status of a review.
     */
    public function updateStatus(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->approved = $request->status;
        $review->save();

        return redirect()->back()->with('success', 'Review status updated successfully');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully');
    }
}
