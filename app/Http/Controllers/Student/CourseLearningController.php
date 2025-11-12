<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CourseLearningController extends Controller
{
    public function show(Course $course)
    {
        $user = Auth::user();
        // Ensure the authenticated user is enrolled as student
        $isEnrolled = $user->enrolledCourses()->where('course_id', $course->id)->exists();
        if (!$isEnrolled) {
            abort(403, 'Bạn không có quyền truy cập khóa học này.');
        }

        $course->load(['sections.lessons', 'sections.tests', 'teachers:id,name,email', 'categories:id,name']);

        return Inertia::render('Student/CourseLearning', [
            'course' => $course,
        ]);
    }
}
