<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // 1. Nếu có khóa ngoại, hãy xóa nó trước (thường tên là bảng_cột_foreign)
            $table->dropForeign(['ticket_id']);

            // 2. Xóa cột ticket_id
            $table->dropColumn('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Định nghĩa lại cột để có thể rollback nếu cần
            $table->unsignedBigInteger('ticket_id')->nullable();
        });
    }
};
