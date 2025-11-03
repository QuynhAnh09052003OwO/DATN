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
        Schema::table('sections', function (Blueprint $table) {
            // Chỉ thêm description nếu chưa tồn tại
            if (!Schema::hasColumn('sections', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            // Chỉ xóa description nếu tồn tại
            if (Schema::hasColumn('sections', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
