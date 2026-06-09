<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeddingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'wedding_date' => ['sometimes', 'nullable', 'date'],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'venue_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'guest_count' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:5000'],
            'total_budget' => ['sometimes', 'nullable', 'numeric', 'min:0'],
        ];
    }
}
