<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'role' => $this->role,
            'locale' => $this->locale,
            'is_premium' => $this->isPremium(),
            'is_admin' => $this->isAdmin(),
            'subscription' => $this->whenLoaded('activeSubscription', fn() => [
                'plan' => $this->activeSubscription?->plan?->name,
                'status' => $this->activeSubscription?->status,
                'ends_at' => $this->activeSubscription?->current_period_end,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
