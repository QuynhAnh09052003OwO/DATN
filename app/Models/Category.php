<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
    ];

    // Relationships
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)->where();
    }

    // Scopes - removed active scope since status column was removed

    // Accessors
    public function getCoursesCountAttribute()
    {
        return $this->courses()->count();
    }
}
