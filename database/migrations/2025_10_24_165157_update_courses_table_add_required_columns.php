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
            // Thêm các cột mới (chỉ khi chưa tồn tại)
            if (!Schema::hasColumn('courses', 'type')) {
                $table->enum('type', ['video', 'zoom'])->default('video')->after('description');
            }
            
            if (!Schema::hasColumn('courses', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->after('type');
            }
            
            if (!Schema::hasColumn('courses', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('image');
            }
            
            if (!Schema::hasColumn('courses', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->after('is_published');
            }
            
            // Xóa các cột không cần thiết (chỉ khi tồn tại)
            $columnsToDrop = [];
            if (Schema::hasColumn('courses', 'category')) {
                $columnsToDrop[] = 'category';
            }
            if (Schema::hasColumn('courses', 'max_students')) {
                $columnsToDrop[] = 'max_students';
            }
            if (Schema::hasColumn('courses', 'is_featured')) {
                $columnsToDrop[] = 'is_featured';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
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
