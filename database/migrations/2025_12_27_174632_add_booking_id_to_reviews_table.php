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
        Schema::table('reviews', function (Blueprint $table) {
            // Thêm cột booking_id sau cột route_id (hoặc vị trí bạn muốn)
            // constrained() giúp tự động tạo khóa ngoại liên kết với bảng bookings
            $table->foreignId('booking_id')
                ->after('route_id')
                ->nullable() // Để nullable nếu bạn đã có dữ liệu cũ chưa có booking_id
                ->constrained('bookings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Xóa khóa ngoại trước khi xóa cột
            $table->dropForeign(['booking_id']);
            $table->dropColumn('booking_id');
        });
    }
};
