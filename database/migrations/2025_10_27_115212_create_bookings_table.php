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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->dateTime('ngay_dat');
            $table->integer('tong_tien')->default(0);
            $table->enum('trang_thai', ['Đang chờ', 'Đã xác nhận', 'Đã thanh toán', 'Đã hủy'])->default('Đang chờ');
            $table->string('phuong_thuc_thanh_toan')->nullable(); // ví dụ: tiền mặt, momo
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
