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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();

            // Thành phố đi – thành phố đến
            $table->unsignedBigInteger('from_city_id');
            $table->unsignedBigInteger('to_city_id');

            // Quãng đường (km)
            $table->integer('distance')->nullable();

            // Thời gian dự kiến (ví dụ 02:30)
            $table->time('estimated_time')->nullable();

            // 1 = active, 0 = inactive
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();

            // Khóa ngoại liên kết cities
            $table->foreign('from_city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreign('to_city_id')->references('id')->on('cities')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
