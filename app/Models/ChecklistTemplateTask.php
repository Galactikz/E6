<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistTemplateTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_template_id', 'title', 'description',
        'months_before_wedding', 'priority', 'category', 'sort_order',
    ];

    protected $casts = [
        'months_before_wedding' => 'integer',
        'sort_order' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }
}
