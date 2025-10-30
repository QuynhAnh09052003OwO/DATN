<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'time_limit',
        'max_attempts',
        'passing_score',
        'order',
        'is_locked',
        'is_required',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'is_required' => 'boolean',
        'time_limit' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'integer',
        'order' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(TestQuestion::class)->orderBy('order');
    }
}

