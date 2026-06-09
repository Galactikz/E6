<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['sometimes', 'string', 'max:100'],
            'icon' => ['sometimes', 'nullable', 'string', 'max:50'],
            'color' => ['sometimes', 'string', 'max:20', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'planned_amount' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
