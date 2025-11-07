<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['categories', 'teachers'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = \App\Models\Category::where('is_active', true)->get(['id', 'name']);

        return Inertia::render('Admin/CoursesManagement/Courses', [
            'courses' => $courses,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        // Tạo khóa học mới rỗng với trạng thái draft và mặc định bị khóa
        $course = Course::create([
            'title' => 'Khóa học mới',
            'description' => '',
            'price' => 0,
            'type' => 'video',
            'status' => 'draft',
            'category_id' => null,
            'image' => null,
            'duration' => 0,
            'is_locked' => true, // Mặc định bị khóa
        ]);

        // Chuyển hướng tới trang chỉnh sửa
        return redirect()->route('admin.courses.edit', $course->id);
    }

    public function edit(Course $course)
    {
        $course->load(['teachers', 'categories']);
        $categories = \App\Models\Category::where('is_active', true)->get(['id', 'name']);
        $teachers = User::where('role', 'teacher')->get(['id', 'name', 'email']);
        
        // Get teacher_ids from course_user relationship
        $course->teacher_ids = $course->teachers->pluck('id')->toArray();
        // Get category_ids from pivot
        $course->category_ids = $course->categories->pluck('id')->toArray();
        
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
            'price' => 'required|numeric|min:0|max:50000000', // Max 50,000,000 VNĐ 
            'type' => 'required|in:video,zoom',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480', // 20MB max
            'duration' => 'nullable|numeric|min:0',
            'is_locked' => 'boolean',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $data = $request->except(['image_file', 'teacher_ids', 'category_ids']);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            
            // Sanitize filename - remove spaces and special characters
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            
            // Replace spaces and special characters with underscores
            $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nameWithoutExt);
            $filename = time() . '_' . $sanitizedName . '.' . $extension;
            
            $path = $file->storeAs('course-images', $filename, 'public');
            $data['image'] = asset('storage/course-images/' . $filename);
        }

        $course->update($data);

        // Sync teachers to course_user table
        if ($request->has('teacher_ids') && is_array($request->teacher_ids)) {
            $teacherIds = array_filter($request->teacher_ids); // Remove empty values
            $teachers = [];
            foreach ($teacherIds as $teacherId) {
                $teachers[$teacherId] = ['role' => 'teacher', 'enrolled_at' => now()];
            }
            $course->teachers()->sync($teachers);
        }

        // Sync categories to category_course pivot (robust for JSON requests)
        $categoryIds = (array) $request->input('category_ids', []);
        $categoryIds = array_values(array_filter($categoryIds, function ($v) { return $v !== null && $v !== ''; }));
        if (!empty($categoryIds)) {
            $course->categories()->sync($categoryIds);
        } else if ($request->has('category_ids')) {
            $course->categories()->sync([]);
        }

        return redirect()->route('admin.courses')
            ->with('success', 'Khóa học đã được cập nhật thành công!');
    }

    public function publish(Course $course)
    {
        $course->update([
            'status' => 'released'
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

    // JSON: fetch sections with lessons for a course
    public function sectionsJson(Course $course)
    {
        $course->load(['sections.lessons', 'sections.tests']);
        return response()->json([
            'sections' => $course->sections->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'order' => $s->order,
                    'description' => $s->description ?? null,
                    'lessons' => $s->lessons->map(function ($l) {
                        return [
                            'id' => $l->id,
                            'title' => $l->title,
                            'description' => $l->description,
                            'attachment' => $l->attachment,
                            'video_url' => $l->video_url,
                            'video_duration' => $l->video_duration,
                            'order' => $l->order,
                            'is_locked' => (bool) $l->is_locked,
                        ];
                    }),
                    'tests' => $s->tests->map(function ($t) {
                        return [
                            'id' => $t->id,
                            'title' => $t->title,
                            'order' => $t->order,
                            'is_locked' => (bool) $t->is_locked,
                        ];
                    }),
                ];
            }),
        ]);
    }
}
