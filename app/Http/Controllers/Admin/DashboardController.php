<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        // Get statistics
        $totalCourses = Course::count();
        $activeCourses = Course::where('status', 'released')->count();
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalCourses' => $totalCourses,
                'activeCourses' => $activeCourses,
                'totalStudents' => $totalStudents,
                'totalTeachers' => $totalTeachers,
            ],
        ]);
    }
}

