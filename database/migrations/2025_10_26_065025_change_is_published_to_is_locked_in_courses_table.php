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
            // Drop is_published column
            $table->dropColumn('is_published');
            
            // Add is_locked column with default true
            $table->boolean('is_locked')->default(true)->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop is_locked column
            $table->dropColumn('is_locked');
            
            // Add back is_published column
            $table->boolean('is_published')->default(false)->after('duration');
        });
    }
};
