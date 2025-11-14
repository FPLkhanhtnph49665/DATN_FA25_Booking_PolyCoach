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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // BIGINT PK
            $table->string('user_code', 50)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('full_name', 200); // có thể auto generate
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable(); // từ Laravel mặc định
            $table->string('phone', 20)->nullable();
            $table->string('password'); // mật khẩu đã hash
            $table->string('image')->nullable();
            $table->enum('role', ['admin','user'])->default('user'); // phân quyền
            $table->tinyInteger('status')->default(1); // 1: active, 0: blocked
            $table->rememberToken(); // từ Laravel mặc định
            $table->timestamps();
            $table->softDeletes(); // deleted_at
        });

        // password reset table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
