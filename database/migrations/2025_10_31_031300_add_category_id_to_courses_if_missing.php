<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('courses', 'category_id')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('courses', 'category_id')) {
            Schema::table('courses', function (Blueprint $table) {
                // Drop FK then column for portability
                try {
                    $table->dropConstrainedForeignId('category_id');
                } catch (Throwable $e) {
                    // Fallback for platforms without helper
                    $table->dropForeign(['category_id']);
                    $table->dropColumn('category_id');
                    return;
                }
            });
        }
    }
};


