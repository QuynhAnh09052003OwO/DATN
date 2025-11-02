<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')->orderBy('name')->get();
        
        return Inertia::render('Admin/CoursesManagement/Categories', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/CoursesManagement/CategoryForm', [
            'category' => null
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
            'is_active' => true,
        ]);

        return redirect()->route('admin.courses.categories')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Admin/CoursesManagement/CategoryForm', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
        ]);

        return redirect()->route('admin.courses.categories')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra xem category có courses không
        if ($category->courses->isEmpty()) {
            $category->delete();

            return redirect()->route('admin.courses.categories')
                ->with('success', 'Danh mục đã được xóa thành công!');
        }else{
            return redirect()->route('admin.courses.categories')
            ->with('error', 'Không thể xóa danh mục này vì còn có khóa học liên quan!');
        }

       
    }
}
