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
            $table->enum('type', ['video', 'zoom'])->default('video');
            $table->integer('price')->default(0);
            $table->string('image')->nullable();
            $table->enum('status', ['draft', 'released'])->default('draft');
            $table->decimal('duration', 8, 2)->nullable(); // Store as hours
            $table->boolean('is_locked')->default(true);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }
};
