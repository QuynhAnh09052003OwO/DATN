<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $section = $course->sections()->create([
            'title' => $validated['title'],
            'order' => $validated['order'] ?? ($course->sections()->max('order') + 1),
        ]);

        return back()->with('success', 'Đã tạo học phần.')->with('section', $section);
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $section->update([
            'title' => $validated['title'],
            'order' => $validated['order'] ?? $section->order,
        ]);

        return back()->with('success', 'Đã cập nhật học phần.');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return back()->with('success', 'Đã xóa học phần.');
    }

    // JSON endpoints for SPA/AJAX usage
    public function storeJson(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $section = $course->sections()->create([
            'title' => $validated['title'],
            'order' => $validated['order'] ?? ($course->sections()->max('order') + 1),
        ]);

        return response()->json(['section' => $section]);
    }

    public function updateJson(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $section->update([
            'title' => $validated['title'],
            'order' => $validated['order'] ?? $section->order,
        ]);

        return response()->json(['section' => $section->fresh()]);
    }
}


