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
        if (Schema::hasTable('test_questions')) {
            return;
        }
        
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            $table->text('question'); // Nội dung câu hỏi
            $table->enum('type', ['multiple_choice', 'true_false', 'fill_blank', 'essay'])->default('multiple_choice');
            $table->json('options')->nullable(); // Các lựa chọn (cho multiple_choice)
            $table->json('correct_answers')->nullable(); // Đáp án đúng
            $table->unsignedInteger('points')->default(1); // Điểm số
            $table->unsignedInteger('order')->default(0); // Thứ tự trong bài test
            $table->string('image')->nullable(); // Ảnh đính kèm câu hỏi
            $table->string('audio')->nullable(); // Audio đính kèm câu hỏi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};

