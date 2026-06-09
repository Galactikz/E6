<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'notes' => $this->notes,
            'planned_amount' => (float) $this->planned_amount,
            'actual_amount' => (float) $this->actual_amount,
            'is_paid' => $this->is_paid,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'vendor_name' => $this->vendor_name,
            'status' => $this->status,
            'budget_category_id' => $this->budget_category_id,
        ];
    }
}
