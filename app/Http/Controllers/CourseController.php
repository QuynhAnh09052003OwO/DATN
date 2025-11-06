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

    public function show(Course $course)
    {
        $course->load([
            'categories:id,name',
            'teachers:id,name,email',
            'sections.lessons',
            'sections.tests',
        ]);

        // Check if user is enrolled as student
        $isEnrolled = false;
        if (auth()->check()) {
            $isEnrolled = $course->students()->where('user_id', auth()->id())->exists();
        }

        return Inertia::render('CourseShow', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'type' => $course->type,
                'price' => $course->price,
                'image' => $course->image,
                'duration' => $course->duration,
                'status' => $course->status,
                'is_enrolled' => $isEnrolled,
                'categories' => $course->categories->map->only(['id','name'])->values(),
                'teachers' => $course->teachers->map->only(['id','name','email'])->values(),
                'sections' => $course->sections->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'title' => $s->title,
                        'description' => $s->description,
                        'order' => $s->order,
                        'lessons' => $s->lessons->map(function ($l) {
                            return [
                                'id' => $l->id,
                                'title' => $l->title,
                                'description' => $l->description,
                                'attachment' => $l->attachment,
                                'video_url' => $l->video_url,
                                'video_duration' => $l->video_duration,
                                'order' => $l->order,
                                'is_locked' => $l->is_locked,
                            ];
                        })->values(),
                        'tests' => $s->tests->map(function ($t) {
                            return [
                                'id' => $t->id,
                                'title' => $t->title,
                                'time_limit' => $t->time_limit,
                                'max_attempts' => $t->max_attempts,
                                'passing_score' => $t->passing_score,
                                'order' => $t->order,
                                'is_locked' => (bool) $t->is_locked,
                                'is_required' => (bool) $t->is_required,
                            ];
                        })->values(),
                    ];
                })->values(),
            ],
        ]);
    }

    public function enroll(Course $course)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login.student')->with('error', 'Vui lòng đăng nhập để tham gia khóa học.');
        }

        // Check if user is already enrolled
        $user = auth()->user();
        if ($course->students()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('info', 'Bạn đã tham gia khóa học này rồi.');
        }

        // Enroll user as student
        $course->students()->attach($user->id, [
            'role' => 'student',
            'enrolled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Bạn đã tham gia khóa học thành công!');
    }
}
