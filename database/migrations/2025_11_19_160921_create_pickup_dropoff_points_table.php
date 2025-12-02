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
        Schema::create('pickup_dropoff_points', function (Blueprint $table) {
            $table->id();

            // Thành phố chứa điểm
            $table->unsignedBigInteger('city_id')->nullable();

            // Tuyến đường
            $table->unsignedBigInteger('route_id');

            // Tên điểm đón/trả
            $table->string('name');

            // Địa chỉ cụ thể (optional)
            $table->string('address')->nullable();

            // Giờ xe đi qua điểm này (optional)
            $table->time('time')->nullable();

            // Loại điểm: pickup / dropoff
            $table->enum('type', ['pickup', 'dropoff']);

            // Điểm còn hoạt động hay không
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete();
            $table->foreign('route_id')->references('id')->on('routes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_dropoff_points');
    }
};
