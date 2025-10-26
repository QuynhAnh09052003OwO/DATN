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
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role')->default('student'); // 'student' hoặc 'teacher'
            $table->timestamp('enrolled_at')->nullable(); // Thời gian đăng ký (cho student) hoặc được phân công (cho teacher)
            $table->timestamps();
            
            // Composite unique constraint: 1 user chỉ có thể có 1 role trong 1 course
            $table->unique(['course_id', 'user_id', 'role'], 'course_user_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_user');
    }
};
