<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'priority' => $this->priority,
            'status' => $this->status,
            'category' => $this->category,
            'notes' => $this->notes,
            'sort_order' => $this->sort_order,
            'is_overdue' => $this->due_date && $this->due_date->isPast() && $this->status !== 'done',
            'created_at' => $this->created_at,
        ];
    }
}
