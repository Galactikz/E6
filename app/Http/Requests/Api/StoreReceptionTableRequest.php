<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceptionTableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'shape' => ['required', 'in:round,rectangular'],
            'capacity' => ['required', 'integer', 'min:1', 'max:30'],
            'position' => ['sometimes', 'nullable', 'array'],
            'position.x' => ['sometimes', 'numeric'],
            'position.y' => ['sometimes', 'numeric'],
            'color' => ['sometimes', 'string', 'max:20'],
        ];
    }
}
