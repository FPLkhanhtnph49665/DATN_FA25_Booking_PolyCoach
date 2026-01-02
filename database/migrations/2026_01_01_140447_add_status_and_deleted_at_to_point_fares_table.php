<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('point_fares', function (Blueprint $table) {
            // Thêm cột status (mặc định là 1) sau cột price
            $table->tinyInteger('status')->default(1)->comment('1: active, 0: inactive')->after('price');

            // Thêm cột deleted_at để dùng tính năng Soft Delete của Laravel
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_fares', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
};
