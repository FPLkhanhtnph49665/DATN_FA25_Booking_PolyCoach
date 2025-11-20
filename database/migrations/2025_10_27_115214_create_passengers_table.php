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
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();

            // Quan hệ với vé
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');

            // Thông tin hành khách
            $table->string('name', 150);
            $table->string('phone', 20)->nullable();
            $table->integer('age')->nullable();

            // Số ghế (vd: A1, B2…)
            $table->string('seat_number', 5);

            $table->timestamps();
            $table->softDeletes();

            // Index để tìm nhanh theo vé / số ghế
            $table->index(['ticket_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passengers');
    }
};
