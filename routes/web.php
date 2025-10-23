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
Route::get('/courses', function () {
    return Inertia::render('Courses');
})->name('courses');

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

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');
    
    Route::get('/courses', function () {
        return Inertia::render('Admin/Courses');
    })->name('courses');
    
    Route::get('/teachers', function () {
        return Inertia::render('Admin/Teachers');
    })->name('teachers');
    
    Route::get('/students', function () {
        return Inertia::render('Admin/Students');
    })->name('students');
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
