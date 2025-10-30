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
        if (Schema::hasTable('answers')) {
            return;
        }
        
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_question_id')->constrained('test_questions')->cascadeOnDelete();
            $table->string('context');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};


