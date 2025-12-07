<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bus_seats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bus_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('code', 10);       // A01, B12...
            $table->tinyInteger('floor')->default(1);  // 1: tầng dưới, 2: tầng trên
            $table->tinyInteger('row')->nullable();    // hàng
            $table->tinyInteger('col')->nullable();    // cột

            // 1 = dùng được, 0 = khóa/hỏng/không bán
            $table->tinyInteger('status')->default(1);

            $table->string('note')->nullable();

            $table->timestamps();

            $table->unique(['bus_id', 'code']); // mỗi ghế trên 1 xe là duy nhất
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_seats');
    }
};
