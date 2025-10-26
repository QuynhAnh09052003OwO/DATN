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
        'canRegister' => Features::enabled(Features::registration()),
        'userType' => 'teacher',
        'status' => session('status'),
    ]);
})->name('login.teacher');

Route::get('/login/admin', function () {
    return Inertia::render('auth/Login', [
        'canResetPassword' => Features::enabled(Features::resetPasswords()),
        'canRegister' => Features::enabled(Features::registration()),
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
        $user = auth()->user();
        
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
    Route::get('/teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teachers.store');
    
    // Students Management
    Route::get('/students', function () {
        return Inertia::render('Admin/Students');
    })->name('students');
    
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
        return Inertia::render('Student/Courses');
    })->name('courses');
    
    Route::get('/profile', function () {
        return Inertia::render('Student/Profile');
    })->name('profile');
});

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/settings.php';
