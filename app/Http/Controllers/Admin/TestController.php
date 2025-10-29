<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use Inertia\Inertia;

class TestController extends Controller
{
    public function create(Course $course, Section $section)
    {
        return Inertia::render('Admin/CoursesManagement/TestCreate', [
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
            ],
            'section' => [
                'id' => $section->id,
                'title' => $section->title,
            ],
            'questions' => [],
        ]);
    }
}


