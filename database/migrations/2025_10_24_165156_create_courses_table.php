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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('price')->default(0);
            $table->string('image')->nullable();
            $table->enum('status', ['draft', 'released'])->default('draft');
            $table->decimal('duration', 8, 2)->nullable(); // Store as hours
            $table->string('category')->nullable(); // Will be replaced by category_id
            $table->integer('max_students')->nullable(); // Will be dropped
            $table->boolean('is_featured')->default(false); // Will be dropped
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
