<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        // Filter theo category
        if ($request->filled('category')) {
            $query->category($request->category);
        }

        // Filter theo status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Search theo title
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Lấy danh sách categories để hiển thị trong filter
        $categories = Course::distinct()->pluck('category')->filter()->values();

        // Phân trang
        $perPage = $request->get('per_page', 15);
        $courses = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
            'categories' => $categories,
            'filters' => $request->only(['category', 'status', 'search', 'per_page']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Courses/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'status' => 'required|in:draft,released',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'max_students' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Khóa học đã được tạo thành công!');
    }

    public function show(Course $course)
    {
        return Inertia::render('Admin/Courses/Show', [
            'course' => $course,
        ]);
    }

    public function edit(Course $course)
    {
        return Inertia::render('Admin/Courses/Edit', [
            'course' => $course,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'status' => 'required|in:draft,released',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'max_students' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Khóa học đã được cập nhật thành công!');
    }

    public function publish(Course $course)
    {
        // Chỉ cho phép publish nếu course đang ở trạng thái draft
        if ($course->status !== 'draft') {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Chỉ có thể phát hành khóa học đang ở trạng thái bản nháp.');
        }

        $course->update(['status' => 'released']);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Khóa học đã được phát hành thành công!');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Khóa học đã được xóa thành công!');
    }
}
