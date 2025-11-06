<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use App\Models\Course;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')
            ->withCount('taughtCourses')
            ->with('taughtCourses:id,title')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'phone' => $teacher->phone ?? 'N/A',
                    'gender' => $teacher->gender ?? 'N/A',
                    'courses_count' => $teacher->taught_courses_count ?? 0,
                    'courses' => $teacher->taughtCourses->pluck('title')->toArray(),
                    'created_at' => $teacher->created_at->toDateString(),
                ];
            });

        return Inertia::render('Admin/TeachersManagement/Teachers', [
            'teachers' => $teachers,
        ]);
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return Inertia::render('Admin/TeachersManagement/CreateTeacher');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $teacher = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Tài khoản giáo viên đã được tạo thành công!');
    }

    public function edit(User $teacher)
    {
        abort_unless($teacher->role === 'teacher', 404);
        $teacher->load(['taughtCourses:id,title']);
        $courses = Course::orderBy('title')->get(['id','title']);

        return Inertia::render('Admin/TeachersManagement/TeacherEdit', [
            'teacher' => [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'gender' => $teacher->gender,
                'course_ids' => $teacher->taughtCourses->pluck('id'),
            ],
            'courses' => $courses,
        ]);
    }

    public function update(Request $request, User $teacher)
    {
        abort_unless($teacher->role === 'teacher', 404);

        $validated = $request->validate([
            'name' => ['nullable','string','max:255'],
            'email' => ['nullable','string','email','max:255', Rule::unique('users', 'email')->ignore($teacher->id)],
            'phone' => ['nullable','string','max:20'],
            'gender' => ['nullable', Rule::in(['male','female','other'])],
            'course_ids' => ['nullable','array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $teacher->update(array_filter([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ], fn($v) => !is_null($v)));

        if ($request->has('course_ids')) {
            $ids = array_filter((array) $request->input('course_ids', []), fn($v) => $v !== null && $v !== '');
            $sync = [];
            foreach ($ids as $id) {
                $sync[$id] = ['role' => 'teacher', 'enrolled_at' => now()];
            }
            $teacher->taughtCourses()->sync($sync);
        }

        return redirect()->route('admin.teachers')->with('success', 'Đã cập nhật giáo viên');
    }

    public function destroy(User $teacher)
    {
        abort_unless($teacher->role === 'teacher', 404);
        $teacher->taughtCourses()->detach();
        $teacher->delete();
        return redirect()->route('admin.teachers')->with('success', 'Đã xóa giáo viên');
    }
}

