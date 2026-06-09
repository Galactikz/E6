<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeddingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'wedding_date' => ['nullable', 'date', 'after:today'],
            'city' => ['nullable', 'string', 'max:100'],
            'venue_name' => ['nullable', 'string', 'max:255'],
            'guest_count' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'total_budget' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
