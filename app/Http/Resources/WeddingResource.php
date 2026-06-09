<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeddingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'wedding_date' => $this->wedding_date?->format('Y-m-d'),
            'city' => $this->city,
            'venue_name' => $this->venue_name,
            'guest_count' => $this->guest_count,
            'total_budget' => (float) $this->total_budget,
            'is_active' => $this->is_active,
            'budget_categories' => BudgetCategoryResource::collection($this->whenLoaded('budgetCategories')),
            'guest_groups' => GuestGroupResource::collection($this->whenLoaded('guestGroups')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
