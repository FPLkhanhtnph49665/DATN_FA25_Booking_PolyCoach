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
        Schema::table('pickup_points', function (Blueprint $table) {
            // Tạo cột city_id kiểu BigInt Unsigned và tự động tạo khóa ngoại
            $table->foreignId('city_id')
                ->nullable()             // Cho phép null
                ->after('route_id')      // Đặt sau cột route_id
                ->constrained('cities')  // Liên kết tới bảng cities
                ->onDelete('set null');  // Khi xóa city thì set cột này về null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickup_points', function (Blueprint $table) {
            // Xóa khóa ngoại trước rồi mới xóa cột
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
        });
    }
};
