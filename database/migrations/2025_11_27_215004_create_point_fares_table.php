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
        Schema::create('point_fares', function (Blueprint $table) {
            $table->id();
            // 1. Liên kết với Route để dễ quản lý/truy vấn
            $table->foreignId('route_id')->constrained()->onDelete('cascade');

            // 2. Điểm đón (Pickup)
            $table->foreignId('pickup_point_id')->constrained('pickup_points')->onDelete('cascade');

            // 3. Điểm trả (Dropoff)
            $table->foreignId('dropoff_point_id')->constrained('dropoff_points')->onDelete('cascade');

            // 4. Giá vé cụ thể cho chặng này
            $table->decimal('price', 10, 2); 

            // Đảm bảo không có 2 dòng trùng lặp cho cùng 1 cặp điểm đón/trả
            $table->unique(['pickup_point_id', 'dropoff_point_id'], 'unique_point_fare');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_fares');
    }
};
