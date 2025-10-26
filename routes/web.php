<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Courses page for guest users
Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses');

// Register route được xử lý bởi Fortify

// Login selector - trang chọn loại tài khoản đăng nhập
Route::get('/login', function () {
    return Inertia::render('auth/LoginSelector');
})->name('login.selector');

// Login routes cho từng loại user
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

// POST route for admin login
Route::post('/login/admin', function () {
    return redirect()->route('login.admin');
})->name('login.admin.post');

// Test route để kiểm tra
Route::get('/test-home', function () {
    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('test-home');

// Test route để kiểm tra database
Route::get('/test-db', function () {
    try {
        $users = \App\Models\User::all(['id', 'name', 'email', 'role', 'password']);
        return response()->json([
            'success' => true,
            'users' => $users,
            'count' => $users->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test route để kiểm tra authentication
Route::post('/test-login', function (\Illuminate\Http\Request $request) {
    try {
        $credentials = $request->only('email', 'password');
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => true,
                'user' => $user,
                'message' => 'Login successful'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test page để test login
Route::get('/test-login-page', function () {
    return '<!DOCTYPE html>
<html>
<head>
    <title>Test Login</title>
    <meta name="csrf-token" content="' . csrf_token() . '">
</head>
<body>
    <h1>Test Login</h1>
    <form id="loginForm">
        <div>
            <label>Email:</label>
            <input type="email" id="email" value="admin@doraedu.com" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" id="password" value="password" required>
        </div>
        <button type="submit">Test Login</button>
    </form>
    <div id="result"></div>
    
    <script>
        document.getElementById("loginForm").addEventListener("submit", function(e) {
            e.preventDefault();
            
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            
            fetch("/test-login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                },
                body: JSON.stringify({ email: email, password: password })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("result").innerHTML = "<pre>" + JSON.stringify(data, null, 2) + "</pre>";
            })
            .catch(error => {
                document.getElementById("result").innerHTML = "<pre>Error: " + error + "</pre>";
            });
        });
    </script>
</body>
</html>';
});

// Test route để tạo user test
Route::get('/create-test-user', function () {
    try {
        // Delete existing test user
        \App\Models\User::where('email', 'test@admin.com')->delete();
        
        // Create new test user
        $user = \App\Models\User::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test user created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password_hash' => substr($user->password, 0, 20) . '...'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Create admin user with specific email
Route::get('/create-admin-user', function () {
    try {
        // Delete existing admin user
        \App\Models\User::where('email', 'admin@localhost.com')->delete();
        
        // Create new admin user
        $user = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@localhost.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Admin user created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password_hash' => substr($user->password, 0, 20) . '...'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Create teacher user
Route::get('/create-teacher-user', function () {
    try {
        // Delete existing teacher user
        \App\Models\User::where('email', 'teacher@doraedu.com')->delete();
        
        // Create new teacher user
        $user = \App\Models\User::create([
            'name' => 'Nguyễn Văn Giáo',
            'email' => 'teacher@doraedu.com',
            'password' => bcrypt('123456'),
            'role' => 'teacher',
            'gender' => 'male',
            'phone' => '0123456789',
            'email_verified_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Teacher user created successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'gender' => $user->gender,
                'phone' => $user->phone,
                'password_hash' => substr($user->password, 0, 20) . '...'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Create multiple sample teachers
Route::get('/create-sample-teachers', function () {
    try {
        $teachers = [
            [
                'name' => 'Trần Thị Minh',
                'email' => 'minh.teacher@doraedu.com',
                'password' => bcrypt('123456'),
                'role' => 'teacher',
                'gender' => 'female',
                'phone' => '0987654321',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lê Văn Hùng',
                'email' => 'hung.teacher@doraedu.com',
                'password' => bcrypt('123456'),
                'role' => 'teacher',
                'gender' => 'male',
                'phone' => '0369852147',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Phạm Thị Lan',
                'email' => 'lan.teacher@doraedu.com',
                'password' => bcrypt('123456'),
                'role' => 'teacher',
                'gender' => 'female',
                'phone' => '0741258963',
                'email_verified_at' => now(),
            ]
        ];
        
        $createdTeachers = [];
        
        foreach ($teachers as $teacherData) {
            // Delete existing teacher with same email
            \App\Models\User::where('email', $teacherData['email'])->delete();
            
            // Create new teacher
            $user = \App\Models\User::create($teacherData);
            $createdTeachers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'gender' => $user->gender,
                'phone' => $user->phone,
            ];
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Sample teachers created successfully',
            'teachers' => $createdTeachers,
            'count' => count($createdTeachers)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes - chỉ admin mới có thể truy cập
Route::middleware(['auth', 'ensure.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Courses routes
    Route::get('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'index'])->name('courses');
    Route::post('/courses', [\App\Http\Controllers\Admin\CourseController::class, 'create'])->name('courses.create');
    Route::get('/courses/{course}/edit', [\App\Http\Controllers\Admin\CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'update'])->name('courses.update');
    Route::patch('/courses/{course}/publish', [\App\Http\Controllers\Admin\CourseController::class, 'publish'])->name('courses.publish');
    Route::delete('/courses/{course}', [\App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('courses.destroy');
    
    // Categories routes
    Route::get('/courses/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('courses.categories');
    Route::get('/courses/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('courses.categories.create');
    Route::post('/courses/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('courses.categories.store');
    Route::get('/courses/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('courses.categories.edit');
    Route::put('/courses/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('courses.categories.update');
    Route::delete('/courses/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('courses.categories.destroy');
    
    Route::get('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teachers');
    Route::get('/teachers/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [\App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teachers.store');
    
    Route::get('/students', function () {
        return Inertia::render('Admin/Students');
    })->name('students');
    
    Route::get('/users', function () {
        return Inertia::render('Admin/Users');
    })->name('users');
});

// Teacher routes
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

// Student routes
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

require __DIR__.'/settings.php';
