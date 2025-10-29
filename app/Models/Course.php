<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Course extends Model
{

    protected $fillable = [
        'title',
        'description',
        'price',
        'type',
        'status',
        'category_id',
        'image',
        'duration',
        'is_locked',
    ];

    protected $casts = [
        'price' => 'integer', // Store as integer (VNĐ)
        'duration' => 'decimal:2', // Store as hours (decimal)
        'is_locked' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Many-to-many relationship with users
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                    ->withPivot('role', 'enrolled_at')
                    ->withTimestamps();
    }

    // Students enrolled in this course
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                    ->wherePivot('role', 'student')
                    ->withPivot('role', 'enrolled_at')
                    ->withTimestamps();
    }

    // Teachers assigned to this course
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                    ->wherePivot('role', 'teacher')
                    ->withPivot('role', 'enrolled_at')
                    ->withTimestamps();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }


    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeReleased($query)
    {
        return $query->where('status', 'released');
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }
    
    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    public function scopeVideo($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeZoom($query)
    {
        return $query->where('type', 'zoom');
    }

    // Accessors
    public function getStatusTextAttribute()
    {
        return $this->status === 'released' ? 'Đã phát hành' : 'Bản nháp';
    }

    public function getTypeTextAttribute()
    {
        return $this->type === 'video' ? 'Video' : 'Zoom';
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return 'Chưa xác định';
        
        // duration now represents hours directly (e.g., 1.5 = 1 giờ 30 phút)
        $durationValue = (float) $this->duration;
        $hours = floor($durationValue);
        $decimalPart = $durationValue - $hours;
        $minutes = round($decimalPart * 60);
        
        if ($hours > 0 && $minutes > 0) {
            return $hours . ' giờ ' . $minutes . ' phút';
        } elseif ($hours > 0) {
            return $hours . ' giờ';
        } else {
            return $minutes . ' phút';
        }
    }
}
