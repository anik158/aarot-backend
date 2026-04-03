<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'zip_code' => $this->zip_code,
            'profile_image' => $this->profile_image ? asset('storage/images/users/'.$this->profile_image) : 'https://cdn.pixabay.com/photo/2023/02/18/11/00/icon-7797704_640.png',
            'profile_completed' => $this->profile_completed,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
