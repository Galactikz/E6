<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'color' => ['sometimes', 'string', 'max:20', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }
}
