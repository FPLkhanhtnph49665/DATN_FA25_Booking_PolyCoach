<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // Mã vé duy nhất (VD: TCK20251207-ABC123)
            $table->string('code', length: 20)->unique();

            // Quan hệ chuyến xe
            $table->foreignId('trip_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Người mua vé (user)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Giá vé
            $table->unsignedInteger('price')->default(0);

            // Số ghế — thêm vào để tránh lỗi logic liên quan seat_number
            $table->string('seat_code', 10)->nullable();

            // Trạng thái vé
            $table->enum('status', ['pending', 'paid', 'cancelled'])
                  ->default('pending');

            // Phương thức thanh toán
            $table->string('payment_method', 50)->nullable();

            // Kiểm soát vé (checker)
            $table->timestamp('checked_at')->nullable();

            $table->foreignId('checked_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Tối ưu query
            $table->index(['trip_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
