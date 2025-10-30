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

    public function storeJson(Section $section, \Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'time_limit' => ['nullable', 'integer', 'min:0'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'passing_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_locked' => ['nullable', 'boolean'],
            'is_required' => ['nullable', 'boolean'],
            'questions' => ['nullable', 'string'], // JSON string
        ]);

        $test = $section->tests()->create([
            'title' => $validated['title'],
            'time_limit' => $validated['time_limit'] ?? null,
            'max_attempts' => $validated['max_attempts'] ?? 1,
            'passing_score' => $validated['passing_score'] ?? 50,
            'order' => $validated['order'] ?? ($section->tests()->max('order') + 1),
            'is_locked' => (bool)($validated['is_locked'] ?? true),
            'is_required' => (bool)($validated['is_required'] ?? false),
        ]);

        $questions = [];
        if ($request->filled('questions')) {
            $decoded = json_decode($request->input('questions'), true) ?: [];
            foreach ($decoded as $index => $q) {
                $imagePath = null;
                $audioPath = null;
                if ($request->hasFile("image_{$index}")) {
                    $imageStored = $request->file("image_{$index}")->store('test-questions/images', 'public');
                    $imagePath = asset('storage/' . $imageStored);
                }
                if ($request->hasFile("audio_{$index}")) {
                    $audioStored = $request->file("audio_{$index}")->store('test-questions/audios', 'public');
                    $audioPath = asset('storage/' . $audioStored);
                }

                $options = array_map(function ($a) { return (string)($a['text'] ?? ''); }, $q['answers'] ?? []);
                $correctIndex = isset($q['correctIndex']) ? [(int)$q['correctIndex']] : [];

                $question = $test->questions()->create([
                    'question' => (string)($q['title'] ?? ''),
                    'type' => 'multiple_choice',
                    'options' => $options ?: null,
                    'correct_answers' => $correctIndex ?: null,
                    'points' => (int)($q['points'] ?? 1),
                    'order' => (int)($q['order'] ?? ($index + 1)),
                    'image' => $imagePath,
                    'audio' => $audioPath,
                ]);
                
                // Create answers rows - chỉ tạo nếu có answers
                if (!empty($q['answers']) && is_array($q['answers'])) {
                    foreach ($q['answers'] as $aIdx => $ans) {
                        if (!empty($ans['text'])) {
                            $question->answers()->create([
                                'context' => (string)($ans['text']),
                                'is_correct' => isset($q['correctIndex']) && (int)$q['correctIndex'] === (int)$aIdx,
                            ]);
                        }
                    }
                }
                $questions[] = $question;
            }
        }

        return response()->json([
            'test' => $test->fresh(['section']),
            'questions' => $questions,
        ]);
    }
}


