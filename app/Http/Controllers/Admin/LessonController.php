<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function store(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:51200'], // 50MB
            'video_url' => ['nullable', 'string', 'max:2048'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/*', 'max:512000'], // 500MB
            'video_duration' => ['nullable', 'integer', 'min:0'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_locked' => ['nullable', 'boolean'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('lesson-attachments', 'public');
        }

        // Accept either direct URL or uploaded video file
        $videoUrl = $validated['video_url'] ?? null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('lesson-videos', 'public');
            $videoUrl = asset('storage/' . $videoPath);
        }

        $lesson = $section->lessons()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'attachment' => $attachmentPath ? asset('storage/' . $attachmentPath) : null,
            'video_url' => $videoUrl,
            'video_duration' => $validated['video_duration'] ?? null,
            'order' => $validated['order'] ?? ($section->lessons()->max('order') + 1),
            'is_locked' => (bool)($validated['is_locked'] ?? false),
        ]); 

        return back()->with('success', 'Đã tạo bài học.')->with('lesson', $lesson);
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'video_url' => ['nullable', 'string', 'max:2048'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/*', 'max:512000'],
            'video_duration' => ['nullable', 'integer', 'min:0'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_locked' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('lesson-attachments', 'public');
            $lesson->attachment = asset('storage/' . $path);
        }

        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('lesson-videos', 'public');
            $lesson->video_url = asset('storage/' . $videoPath);
        } elseif (isset($validated['video_url'])) {
            $lesson->video_url = $validated['video_url'];
        }

        $lesson->title = $validated['title'];
        $lesson->description = $validated['description'] ?? $lesson->description;
        $lesson->video_duration = $validated['video_duration'] ?? $lesson->video_duration;
        $lesson->order = $validated['order'] ?? $lesson->order;
        $lesson->is_locked = (bool)($validated['is_locked'] ?? $lesson->is_locked);
        $lesson->save();

        return back()->with('success', 'Đã cập nhật bài học.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return back()->with('success', 'Đã xóa bài học.');
    }

    public function destroyJson(Lesson $lesson)
    {
        $lesson->delete();
        return response()->noContent();
    }

    // JSON endpoint for SPA/AJAX usage
    public function storeJson(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'video_url' => ['nullable', 'string', 'max:2048'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/*', 'max:512000'],
            'video_duration' => ['nullable', 'integer', 'min:0'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_locked' => ['nullable', 'boolean'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('lesson-attachments', 'public');
        }

        $videoUrl = $validated['video_url'] ?? null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('lesson-videos', 'public');
            $videoUrl = asset('storage/' . $videoPath);
        }

        $lesson = $section->lessons()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'attachment' => $attachmentPath ? asset('storage/' . $attachmentPath) : null,
            'video_url' => $videoUrl,
            'video_duration' => $validated['video_duration'] ?? null,
            'order' => $validated['order'] ?? ($section->lessons()->max('order') + 1),
            'is_locked' => (bool)($validated['is_locked'] ?? false),
        ]);

        return response()->json(['lesson' => $lesson]);
    }

    public function updateJson(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'video_url' => ['nullable', 'string', 'max:2048'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/*', 'max:512000'],
            'video_duration' => ['nullable', 'integer', 'min:0'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_locked' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('lesson-attachments', 'public');
            $lesson->attachment = asset('storage/' . $path);
        }

        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('lesson-videos', 'public');
            $lesson->video_url = asset('storage/' . $videoPath);
        } elseif (isset($validated['video_url'])) {
            $lesson->video_url = $validated['video_url'];
        }

        $lesson->title = $validated['title'];
        $lesson->description = $validated['description'] ?? $lesson->description;
        $lesson->video_duration = $validated['video_duration'] ?? $lesson->video_duration;
        $lesson->order = $validated['order'] ?? $lesson->order;
        $lesson->is_locked = (bool)($validated['is_locked'] ?? $lesson->is_locked);
        $lesson->save();

        return response()->json(['lesson' => $lesson->fresh()]);
    }
}


