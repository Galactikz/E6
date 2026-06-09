<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'color' => $this->color,
            'planned_amount' => (float) $this->planned_amount,
            'actual_amount' => (float) $this->actual_amount,
            'remaining' => $this->remaining_budget,
            'usage_percentage' => $this->usage_percentage,
            'is_custom' => $this->is_custom,
            'items' => BudgetItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
