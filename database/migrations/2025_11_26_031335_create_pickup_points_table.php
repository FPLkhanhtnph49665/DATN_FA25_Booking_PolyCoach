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
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại trỏ đến bảng routes
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');

            $table->string('ten_diem_don');
            $table->string('dia_chi')->nullable();
            $table->integer('order')->default(1)->comment('Thứ tự dừng trên tuyến đường');
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_points');
    }
};
