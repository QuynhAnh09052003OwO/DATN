<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'description',
        'attachment',
        'video_url',
        'video_duration',
        'order',
        'is_published',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'video_duration' => 'integer',
        'order' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}


