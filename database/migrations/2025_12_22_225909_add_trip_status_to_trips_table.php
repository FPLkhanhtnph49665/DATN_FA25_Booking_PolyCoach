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
            // Thêm cột trip_status kiểu tinyInteger, mặc định là 1 (Chưa xuất phát)
            // Đặt sau cột 'status' để bảng gọn gàng
            $table->tinyInteger('trip_status')
                ->default(1)
                ->comment('1: Chưa xuất phát, 2: Đã tạm hoãn, 3: Đã xuất phát, 4: Đã hoàn thành')
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // Xóa cột nếu cần rollback
            $table->dropColumn('trip_status');
        });
    }
};
