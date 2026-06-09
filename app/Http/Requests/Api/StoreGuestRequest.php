<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'guest_group_id' => ['sometimes', 'nullable', 'exists:guest_groups,id'],
            'rsvp_status' => ['sometimes', 'in:pending,confirmed,declined'],
            'meal_type' => ['sometimes', 'in:adult,child,baby'],
            'dietary_requirements' => ['sometimes', 'nullable', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'plus_one' => ['sometimes', 'boolean'],
        ];
    }
}
