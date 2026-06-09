<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTablePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'room_dimensions' => ['sometimes', 'nullable', 'array'],
            'room_dimensions.width' => ['sometimes', 'numeric', 'min:1'],
            'room_dimensions.height' => ['sometimes', 'numeric', 'min:1'],
        ];
    }
}
