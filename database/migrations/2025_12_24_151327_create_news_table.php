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
        Schema::create('news', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('thumbnail')->nullable();
    $table->text('excerpt')->nullable();
    $table->longText('content');
    $table->string('category')->default('Tin tức');
    $table->string('status', 20)->default('draft');
    $table->boolean('is_featured')->default(false);
    $table->date('published_at')->nullable();
    $table->timestamps();
    $table->softDeletes();

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
