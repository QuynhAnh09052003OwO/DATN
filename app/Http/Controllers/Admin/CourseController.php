<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['category', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = \App\Models\Category::get(['id', 'name']);

        return Inertia::render('Admin/CoursesManagement/Courses', [
            'courses' => $courses,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        // Tạo khóa học mới rỗng với trạng thái draft
        $course = Course::create([
            'title' => 'Khóa học mới',
            'description' => '',
            'price' => 0,
            'type' => 'video',
            'status' => 'draft',
            'category_id' => null,
            'image' => null,
            'duration' => 0,
            'is_published' => false,
            'teacher_id' => null,
        ]);

        // Chuyển hướng tới trang chỉnh sửa
        return redirect()->route('admin.courses.edit', $course->id);
    }

    public function edit(Course $course)
    {
        $course->load(['category', 'teacher']);
        $categories = \App\Models\Category::get(['id', 'name']);
        $teachers = User::where('role', 'teacher')->get(['id', 'name', 'email']);
        
        return Inertia::render('Admin/CoursesManagement/CourseEdit', [
            'course' => $course,
            'categories' => $categories,
            'teachers' => $teachers
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:video,zoom',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'duration' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $data = $request->except(['image_file']);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/course-images', $filename);
            $data['image'] = asset('storage/course-images/' . $filename);
        }

        $course->update($data);

        return redirect()->route('admin.courses')
            ->with('success', 'Khóa học đã được cập nhật thành công!');
    }

    public function publish(Course $course)
    {
        $course->update([
            'status' => 'released',
            'is_published' => true
        ]);

        return redirect()->route('admin.courses')
            ->with('success', 'Khóa học đã được phát hành thành công!');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses')
            ->with('success', 'Khóa học đã được xóa thành công!');
    }
}
