<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'category_course', 'category_id', 'course_id')->withTimestamps();
    }

    // Scopes - removed active scope since status column was removed

    // Accessors
    public function getCoursesCountAttribute()
    {
        return $this->courses()->count();
    }
}
