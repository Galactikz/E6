<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'rsvp_status' => $this->rsvp_status,
            'meal_type' => $this->meal_type,
            'dietary_requirements' => $this->dietary_requirements,
            'notes' => $this->notes,
            'plus_one' => $this->plus_one,
            'invitation_sent' => $this->invitation_sent,
            'invitation_sent_at' => $this->invitation_sent_at,
            'rsvp_responded_at' => $this->rsvp_responded_at,
            'group' => new GuestGroupResource($this->whenLoaded('group')),
        ];
    }
}
