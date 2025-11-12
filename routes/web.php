<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses');
Route::get('/courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{course}/enroll', [\App\Http\Controllers\CourseController::class, 'enroll'])->middleware('auth')->name('courses.enroll');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Login routes are handled by Fortify. Below are the custom login pages
| for different user roles.
*/

Route::get('/login', function () {
    return Inertia::render('auth/LoginSelector');
})->name('login.selector');

Route::get('/login/student', function () {
    return Inertia::render('auth/Login', [
        'canResetPassword' => Features::enabled(Features::resetPasswords()),
        'canRegister' => Features::enabled(Features::registration()),
        'userType' => 'student',
        'status' => session('status'),
    ]);
})->name('login.student');

Route::get('/login/teacher', function () {
    return Inertia::render('auth/Login', [
        'canResetPassword' => Features::enabled(Features::resetPasswords()),
        // Disable registration on teacher login
        'canRegister' => false,
        'userType' => 'teacher',
        'status' => session('status'),
    ]);
})->name('login.teacher');

Route::get('/login/admin', function () {
    return Inertia::render('auth/Login', [
        'canResetPassword' => Features::enabled(Features::resetPasswords()),
        // Disable registration on admin login
        'canRegister' => false,
        'userType' => 'admin',
        'status' => session('status'),
    ]);
})->name('login.admin');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Redirect based on user role
        $user = request()->user();
        
        if ($user) {
            return match($user->role) {
                'admin' => redirect('/admin'),
                'teacher' => redirect('/teacher'),
                'student' => redirect('/student'),
                default => Inertia::render('Dashboard'),
            };
        }
        
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'ensure.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Courses Management
    Route::get('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'index'])->name('courses');
    Route::post('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'create'])->name('courses.create');
    Route::get('/courses/{course}/edit', [\App\Http\Controllers\Admin\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'update'])->name('courses.update');
    Route::patch('/courses/{course}/publish', [\App\Http\Controllers\Admin\CourseController::class, 'publish'])->name('courses.publish');
    Route::delete('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('courses.destroy');
    
    // Categories Management
    Route::get('/courses/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('courses.categories');
    Route::get('/courses/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('courses.categories.create');
    Route::post('/courses/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('courses.categories.store');
    Route::get('/courses/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('courses.categories.edit');
    Route::put('/courses/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('courses.categories.update');
    Route::delete('/courses/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('courses.categories.destroy');
    
    // Teachers Management
    Route::get('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teachers');
    Route::get('/teachers/{teacher}/edit', [\App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [\App\Http\Controllers\Admin\TeacherController::class, 'destroy'])->name('teachers.destroy');
    Route::get('/teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teachers.store');

    // Course Sections & Lessons
    Route::get('/courses/{course}/sections-json', [\App\Http\Controllers\Admin\CourseController::class, 'sectionsJson'])->name('courses.sectionsJson');
    Route::post('/courses/{course}/sections', [\App\Http\Controllers\Admin\SectionController::class, 'store'])->name('sections.store');
    Route::post('/courses/{course}/sections-json', [\App\Http\Controllers\Admin\SectionController::class, 'storeJson'])->name('sections.storeJson');
    Route::put('/sections/{section}/json', [\App\Http\Controllers\Admin\SectionController::class, 'updateJson'])->name('sections.updateJson');
    Route::put('/sections/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'destroy'])->name('sections.destroy');
    Route::delete('/sections/{section}/json', [\App\Http\Controllers\Admin\SectionController::class, 'destroyJson'])->name('sections.destroyJson');

    Route::post('/sections/{section}/lessons', [\App\Http\Controllers\Admin\LessonController::class, 'store'])->name('lessons.store');
    Route::post('/sections/{section}/lessons-json', [\App\Http\Controllers\Admin\LessonController::class, 'storeJson'])->name('lessons.storeJson');
    Route::put('/lessons/{lesson}/json', [\App\Http\Controllers\Admin\LessonController::class, 'updateJson'])->name('lessons.updateJson');
    Route::delete('/lessons/{lesson}/json', [\App\Http\Controllers\Admin\LessonController::class, 'destroyJson'])->name('lessons.destroyJson');

    // Tests Management (create page)
    Route::get('/courses/{course}/sections/{section}/tests/create', [\App\Http\Controllers\Admin\TestController::class, 'create'])->name('tests.create');
    Route::get('/courses/{course}/sections/{section}/tests/{test}/edit', [\App\Http\Controllers\Admin\TestController::class, 'edit'])->name('tests.edit');
    Route::post('/sections/{section}/tests-json', [\App\Http\Controllers\Admin\TestController::class, 'storeJson'])->name('tests.storeJson');
    Route::post('/tests/{test}/json', [\App\Http\Controllers\Admin\TestController::class, 'updateJson'])->name('tests.updateJson');
    Route::put('/lessons/{lesson}', [\App\Http\Controllers\Admin\LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [\App\Http\Controllers\Admin\LessonController::class, 'destroy'])->name('lessons.destroy');
    
    // Students Management
    Route::get('/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('students');
    Route::get('/students/{student}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('students.edit');
    Route::post('/students/{student}/courses', [\App\Http\Controllers\Admin\StudentController::class, 'updateCourses'])->name('students.updateCourses');
    
    // Users Management
    Route::get('/users', function () {
        return Inertia::render('Admin/Users');
    })->name('users');
});

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Teacher/Dashboard');
    })->name('dashboard');
    
    Route::get('/courses', function () {
        return Inertia::render('Teacher/Courses');
    })->name('courses');
    
    Route::get('/students', function () {
        return Inertia::render('Teacher/Students');
    })->name('students');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Student/Dashboard');
    })->name('dashboard');

    Route::get('/courses', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        $courses = $user->enrolledCourses()->with(['categories'])->orderBy('created_at', 'desc')->get();
        return Inertia::render('Student/Courses', [
            'courses' => $courses,
        ]);
    })->name('courses');

    Route::get('/courses/{course}/learn', [\App\Http\Controllers\Student\CourseLearningController::class, 'show'])->name('courses.learn');

    Route::get('/profile', function () {
        return Inertia::render('Student/Profile');
    })->name('profile');
});

/*
|--------------------------------------------------------------------------
| Debug Routes (Remove in production)
|--------------------------------------------------------------------------
*/

Route::get('/debug-session', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    $sessionId = \Illuminate\Support\Facades\Session::getId();
    $sessionData = \Illuminate\Support\Facades\Session::all();
    
    return response()->json([
        'authenticated' => \Illuminate\Support\Facades\Auth::check(),
        'user' => $user ? [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ] : null,
        'session_id' => $sessionId,
        'session_driver' => config('session.driver'),
        'session_lifetime' => config('session.lifetime'),
        'session_data' => $sessionData,
        'session_cookie_name' => config('session.cookie'),
        'remember_token_set' => $user ? !empty($user->remember_token) : false,
        'session_config' => [
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'http_only' => config('session.http_only'),
            'same_site' => config('session.same_site'),
        ],
    ]);
})->middleware('web');

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/settings.php';
