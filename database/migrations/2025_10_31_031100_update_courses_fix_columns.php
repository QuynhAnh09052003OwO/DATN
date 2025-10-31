<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'type')) {
                $table->string('type', 20)->default('video');
            }
            if (!Schema::hasColumn('courses', 'is_locked')) {
                $table->boolean('is_locked')->default(true);
            }
            if (Schema::hasColumn('courses', 'is_published')) {
                $table->dropColumn('is_published');
            }
            if (Schema::hasColumn('courses', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('courses', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
            if (!Schema::hasColumn('courses', 'is_published')) {
                $table->boolean('is_published')->default(false);
            }
            if (!Schema::hasColumn('courses', 'category')) {
                $table->string('category')->nullable();
            }
        });
    }
};


