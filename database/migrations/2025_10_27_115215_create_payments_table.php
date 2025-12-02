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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Quan hệ
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Số tiền
            $table->decimal('amount', 10, 2);

            // Phương thức thanh toán
            $table->string('payment_method', 50); // e.g., cash, momo

            // Trạng thái thanh toán
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();
            $table->softDeletes();

            // Index tối ưu tìm theo ticket hoặc user
            $table->index(['ticket_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
