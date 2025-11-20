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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Quan hệ
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // reviewer
            $table->foreignId('trip_id')->constrained()->onDelete('cascade'); // trip

            // Điểm đánh giá: 1-5
            $table->tinyInteger('rating')->default(5)->comment('1-5 stars');

            // Nội dung đánh giá
            $table->text('content')->nullable();

            // Trạng thái duyệt: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('review status');

            $table->timestamps();
            $table->softDeletes();

            // Index để lọc/truy vấn nhanh
            $table->index(['trip_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
