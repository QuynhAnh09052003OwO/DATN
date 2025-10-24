<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'image',
        'price',
        'duration',
        'max_students',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope để lọc theo status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope để lọc theo category
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope để tìm kiếm theo title
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%");
    }

    // Accessor để format status
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'released' ? 'Đã phát hành' : 'Bản nháp';
    }

    // Accessor để format price
    public function getFormattedPriceAttribute()
    {
        return number_format((float) $this->price, 0, ',', '.') . ' VNĐ';
    }

    // Accessor để format duration
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }
        
        return "{$minutes}m";
    }

    // Relationship với Category (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'course_categories');
    }

    // Scope để lọc theo category ID (sử dụng relationship)
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }
}
