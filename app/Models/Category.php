<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate slug from name
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relationship với Course (many-to-many)
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_categories');
    }

    // Scope để lọc categories đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để tìm kiếm theo tên
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    // Accessor để lấy số lượng khóa học
    public function getCoursesCountAttribute()
    {
        return $this->courses()->count();
    }

    // Method để kiểm tra có thể xóa không
    public function canBeDeleted()
    {
        return $this->courses()->count() === 0;
    }
}