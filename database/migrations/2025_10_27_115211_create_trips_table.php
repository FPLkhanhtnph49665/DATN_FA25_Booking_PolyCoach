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
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->date('ngay_khoi_hanh');
            $table->time('gio_khoi_hanh');
            $table->date('ngay_den')->nullable(); // ngày dự kiến đến
            $table->time('gio_den')->nullable();  // giờ dự kiến đến
            $table->decimal('gia_ve', 10, 2);
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
            $table->softDeletes();
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
