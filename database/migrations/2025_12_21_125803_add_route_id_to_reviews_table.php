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
            Schema::table('reviews', function (Blueprint $table) {
                // 1. Tạo cột route_id (đảm bảo kiểu dữ liệu khớp hoàn toàn với bảng routes)
                $table->unsignedBigInteger('route_id')->after('trip_id');

                // 2. Thiết lập khóa ngoại liên kết với cột id của bảng routes
                $table->foreign('route_id')
                    ->references('id')
                    ->on('routes')
                    ->onDelete('cascade'); // Nếu xóa route thì các reviews liên quan cũng tự động xóa
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Phải xóa khóa ngoại trước khi xóa cột
            $table->dropForeign(['route_id']);
            $table->dropColumn('route_id');
        });
    }
};
