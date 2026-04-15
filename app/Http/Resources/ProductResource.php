<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'qty' => $this->qty,
            'price' => $this->price,
            'description' => $this->description,
            'first_image' => $this->first_image ? asset($this->first_image) : null,
            'second_image' => $this->second_image ? asset($this->second_image) : null,
            'third_image' => $this->third_image ? asset($this->third_image) : null,
            'status' => $this->status,
            'attribute_values' => $this->whenLoaded('attributeValues', function () {
                return $this->attributeValues->map(function ($val) {
                    return [
                        'id' => $val->id,
                        'value' => $val->value,
                        'attribute' => $val->attribute ? [
                            'id' => $val->attribute->id,
                            'name' => $val->attribute->name,
                        ] : null
                    ];
                });
            }, []),
            'reviews' => $this->whenLoaded('reviews', function() {
                return $this->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user' => [
                        'name' => $review->user->name ?? 'Anonymous'
                    ],
                    'title' => $review->title,
                    'body' => $review->body,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at,
                ];
            });
            }, []),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
