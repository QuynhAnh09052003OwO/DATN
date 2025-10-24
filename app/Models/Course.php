<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'is_published',
        'teacher_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
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

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
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
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . ' giờ ' . $minutes . ' phút';
        }
        
        return $minutes . ' phút';
    }
}
