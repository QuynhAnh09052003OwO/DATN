<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Thêm các cột mới
            $table->enum('type', ['video', 'zoom'])->default('video')->after('description');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->after('type');
            $table->boolean('is_published')->default(false)->after('image');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->after('is_published');
            
            // Xóa các cột không cần thiết
            $table->dropColumn(['category', 'max_students', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Khôi phục các cột đã xóa
            $table->string('category')->after('status');
            
            // Xóa các cột đã thêm
            $table->dropForeign(['category_id']);
            $table->dropColumn(['type', 'category_id', 'is_published', 'teacher_id']);
        });
    }
};
