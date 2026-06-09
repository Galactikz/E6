<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }
}
