<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Admin/StudentsManagement/Students', [
            'students' => $students,
        ]);
    }

    public function edit(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->load(['enrolledCourses:id,title,image,price']);
        $courses = Course::orderBy('created_at', 'desc')->get(['id', 'title', 'image', 'price']);

        return Inertia::render('Admin/StudentsManagement/StudentEdit', [
            'student' => $student,
            'courses' => $courses,
            'enrolled_course_ids' => $student->enrolledCourses->pluck('id'),
        ]);
    }

    public function updateCourses(Request $request, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $validated = $request->validate([
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $courseIds = (array)($validated['course_ids'] ?? []);

        // Build sync array with role=student
        $syncPayload = [];
        foreach ($courseIds as $cid) {
            $syncPayload[$cid] = ['role' => 'student', 'enrolled_at' => now()];
        }

        $student->courses()->syncWithoutDetaching([]); // ensure relation exists
        // We want to keep teacher assignments intact, so we will detach only rows with role=student and sync them back
        $student->enrolledCourses()->detach();
        if (!empty($syncPayload)) {
            $student->courses()->syncWithoutDetaching($syncPayload);
        }

        return redirect()->route('admin.students.edit', $student->id)
            ->with('success', 'Đã cập nhật khóa học cho học viên.');
    }
}



