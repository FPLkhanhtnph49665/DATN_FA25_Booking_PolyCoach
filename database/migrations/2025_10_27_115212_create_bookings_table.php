<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Quan hệ
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');

            // Ngày giờ đặt vé
            $table->dateTime('booking_datetime');

            // Tổng tiền
            $table->integer('total_amount')->default(0);

            // Trạng thái: pending, confirmed, paid, cancelled
            $table->enum('status', ['pending', 'confirmed', 'paid', 'cancelled'])->default('pending');

            // Phương thức thanh toán (optional)
            $table->string('payment_method')->nullable(); // e.g., cash, momo

            $table->timestamps();
            $table->softDeletes();

            // Index tối ưu tìm vé theo trip và user
            $table->index(['trip_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
