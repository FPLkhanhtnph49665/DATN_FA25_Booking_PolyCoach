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
        Schema::create('bus_images', function (Blueprint $table) {
            $table->id();
            // Tạo khóa ngoại liên kết với bảng buses
            // constrained() sẽ tự hiểu liên kết với cột id trên bảng buses
            // onDelete('cascade') giúp tự động xóa ảnh khi xe bus bị xóa
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');

            $table->string('image_path'); // Lưu đường dẫn file ảnh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_images');
    }
};
