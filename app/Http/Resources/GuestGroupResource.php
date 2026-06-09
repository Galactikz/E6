<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'guest_count' => $this->when(
                $this->resource->relationLoaded('guests') || isset($this->guests_count),
                fn() => $this->guests_count ?? $this->guests->count()
            ),
        ];
    }
}
