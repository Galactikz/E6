<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'city' => $this->city,
            'department' => $this->department,
            'region' => $this->region,
            'price_from' => $this->price_from,
            'price_to' => $this->price_to,
            'rating' => $this->rating,
            'review_count' => $this->review_count,
            'is_premium' => $this->is_premium,
            'is_verified' => $this->is_verified,
            'gallery' => $this->gallery ?? [],
            'category' => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
        ];
    }
}
