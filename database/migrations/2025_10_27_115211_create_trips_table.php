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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade'); // Tuyến đường
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');   // Xe
            $table->date('ngay_khoi_hanh');
            $table->time('gio_khoi_hanh');
            $table->date('ngay_den')->nullable(); // Ngày dự kiến đến
            $table->time('gio_den')->nullable();  // Giờ dự kiến đến
            $table->decimal('gia_ve', 10, 2)->default(0); // Giá vé mặc định
            $table->tinyInteger('trang_thai')->default(1)->comment('1: Hoạt động, 0: Hủy');
            $table->string('ma_chuyen', 50)->unique()->comment('Mã định danh chuyến xe');

            $table->timestamps();
            $table->softDeletes();

            // Index tối ưu khi tìm chuyến
            $table->index(['route_id', 'ngay_khoi_hanh', 'gio_khoi_hanh']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
