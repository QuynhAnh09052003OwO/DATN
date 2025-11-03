<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['categories', 'teachers'])
            ->where('status', 'released');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter (many-to-many)
        if ($request->filled('category')) {
            $categoryId = $request->category;
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        // Process courses to handle image URLs
        $courses->getCollection()->transform(function ($course) {
            // Replace via.placeholder.com URLs with local placeholder
            if ($course->image && str_contains($course->image, 'via.placeholder.com')) {
                $course->image = $this->generatePlaceholderImage($course->title, $course->type);
            }
            return $course;
        });

        return Inertia::render('Courses', [
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'type'])
        ]);
    }

    private function generatePlaceholderImage($title, $type)
    {
        // Generate a simple placeholder using a data URL
        $colors = [
            'video' => ['bg' => '4F46E5', 'text' => 'FFFFFF'],
            'zoom' => ['bg' => '10B981', 'text' => 'FFFFFF']
        ];
        
        $color = $colors[$type] ?? $colors['video'];
        $shortTitle = substr($title, 0, 15);
        
        // Create a simple SVG placeholder (shorter)
        $svg = '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
            <rect width="400" height="300" fill="#' . $color['bg'] . '"/>
            <text x="200" y="140" text-anchor="middle" fill="#' . $color['text'] . '" font-family="Arial" font-size="20" font-weight="bold">' . htmlspecialchars($shortTitle) . '</text>
            <text x="200" y="170" text-anchor="middle" fill="#' . $color['text'] . '" font-family="Arial" font-size="14" opacity="0.8">' . strtoupper($type) . '</text>
        </svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
