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
        if (Schema::hasTable('tests')) {
            return;
        }
        
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->string('title');
            $table->unsignedInteger('time_limit')->nullable(); // phút
            $table->unsignedInteger('max_attempts')->default(1);
            $table->unsignedInteger('passing_score')->default(50); // phần trăm
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_locked')->default(true);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};

