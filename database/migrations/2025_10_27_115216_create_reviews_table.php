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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // người đánh giá
            $table->foreignId('trip_id')->constrained()->onDelete('cascade'); // chuyến xe
            $table->tinyInteger('rating')->default(5)->comment('1-5 sao');
            $table->text('noi_dung')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('trạng thái duyệt');
            $table->timestamps();
            $table->softDeletes();

            // Nếu cần lọc/truy vấn nhanh
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
