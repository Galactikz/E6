<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'planned_amount' => ['sometimes', 'numeric', 'min:0'],
            'actual_amount' => ['sometimes', 'numeric', 'min:0'],
            'is_paid' => ['sometimes', 'boolean'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'vendor_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'in:pending,confirmed,paid,cancelled'],
        ];
    }
}
