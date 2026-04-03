<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReviewRequest;
use App\Models\Admin\Review;
use App\Traits\ResponseStatus;

class ReviewController extends Controller
{
    use ResponseStatus;

    /**
     * Store a newly created review in storage.
     */
    public function store(ReviewRequest $request)
    {
        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id'    => auth('api')->id(),
            'title'      => $request->title,
            'body'       => $request->body,
            'rating'     => $request->rating,
            'approved'   => Review::APPROVED,
        ]);

        $review->load('user');

        return $this->success($review, 'Review submitted successfully');
    }
}
