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

            // Quan hệ
            $table->foreignId('route_id')->constrained()->onDelete('cascade'); // Route
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');   // Bus

            // Ngày giờ khởi hành / dự kiến đến
            $table->date('departure_date');
            $table->time('departure_time');
            $table->date('arrival_date')->nullable();  // dự kiến
            $table->time('arrival_time')->nullable();  // dự kiến

            // Giá vé
            $table->decimal('ticket_price', 10, 2)->default(0);

            // Trạng thái: 1 = active, 0 = cancelled
            $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Cancelled');

            // Mã định danh chuyến
            $table->string('trip_code', 50)->unique()->comment('Unique trip identifier');

            $table->timestamps();
            $table->softDeletes();

            // Index tối ưu tìm kiếm
            $table->index(['route_id', 'departure_date', 'departure_time']);
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
