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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // Quan hệ
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Số ghế
            $table->string('seat_number');
            $table->string('seat_code', 10)->nullable();

            // Trạng thái vé
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');

            // Phương thức thanh toán
            $table->string('payment_method', 50)->nullable(); // e.g., cash, momo

            $table->timestamps();
            $table->softDeletes();

            // Index tối ưu tìm vé theo trip và user
            $table->index(['trip_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
