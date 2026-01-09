<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->foreignId('pickup_point_id')->constrained('pickup_dropoff_points')->onDelete('cascade');
            $table->foreignId('dropoff_point_id')->constrained('pickup_dropoff_points')->onDelete('cascade');
            $table->decimal('price', 10, 2);
             $table->boolean('active')->default(1);
            $table->timestamps();

            $table->unique(['route_id', 'pickup_point_id', 'dropoff_point_id'], 'unique_route_point_fare');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_fares');
    }
};
