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
        Schema::table('trips', function (Blueprint $table) {
            // 1. Thêm cột checked_at
            $table->timestamp('checked_at')->nullable()->after('trip_code');

            // 2. Thêm cột checked_by và thiết lập liên kết
            // constrained('users') sẽ tự hiểu liên kết với cột id của bảng users
            // nullOnDelete() giúp giữ lại dữ liệu chuyến xe ngay cả khi user đó bị xóa
            $table->foreignId('checked_by')
                ->nullable()
                ->after('checked_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // Khi xóa cột phải xóa foreign key trước
            $table->dropForeign(['checked_by']);
            $table->dropColumn(['checked_at', 'checked_by']);
        });
    }
};
