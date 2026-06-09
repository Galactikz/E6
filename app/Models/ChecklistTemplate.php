<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'months_before', 'is_premium', 'is_active',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'months_before' => 'integer',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(ChecklistTemplateTask::class)->orderBy('sort_order');
    }
}
