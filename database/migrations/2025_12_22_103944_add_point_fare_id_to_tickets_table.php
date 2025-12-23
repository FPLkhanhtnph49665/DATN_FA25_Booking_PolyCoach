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
        Schema::table('tickets', function (Blueprint $table) {
            // Thêm cột point_fare_id sau cột trip_id hoặc bất kỳ vị trí nào bạn muốn
            $table->unsignedBigInteger('point_fare_id')->nullable()->after('trip_id');

            // Thiết lập khóa ngoại liên kết với bảng point_fares
            $table->foreign('point_fare_id')
                ->references('id')
                ->on('point_fares')
                ->onDelete('set null'); // Giữ lại vé ngay cả khi thông tin giá chặng bị xóa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['point_fare_id']);
            $table->dropColumn('point_fare_id');
        });
    }
};
