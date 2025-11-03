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
            // Drop is_published column (chỉ khi tồn tại)
            if (Schema::hasColumn('courses', 'is_published')) {
                $table->dropColumn('is_published');
            }
            
            // Add is_locked column with default true (chỉ khi chưa tồn tại)
            if (!Schema::hasColumn('courses', 'is_locked')) {
                $table->boolean('is_locked')->default(true)->after('duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Drop is_locked column (chỉ khi tồn tại)
            if (Schema::hasColumn('courses', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
            
            // Add back is_published column (chỉ khi chưa tồn tại)
            if (!Schema::hasColumn('courses', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('duration');
            }
        });
    }
};
