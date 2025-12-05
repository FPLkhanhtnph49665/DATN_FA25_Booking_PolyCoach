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
        Schema::table('tickets', function (Blueprint $table) {
            // Thêm cột booking_id, là khóa ngoại liên kết đến bảng 'bookings'
            // unsignedBigInteger: Kiểu dữ liệu phù hợp cho ID của Laravel
            $table->foreignId('booking_id')
                  ->nullable() // Tùy chọn: Cho phép giá trị NULL nếu vé có thể tồn tại mà không cần booking ngay
                  ->constrained('bookings') // Chỉ định khóa ngoại đến bảng 'bookings'
                  ->onDelete('cascade')    // Tùy chọn: Xóa vé nếu Booking bị xóa
                  ->after('id');           // Đặt cột này sau cột 'id' (hoặc cột mong muốn)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Loại bỏ khóa ngoại trước
            $table->dropConstrainedForeignId('booking_id'); 
            
            // Loại bỏ cột
            // Nếu bạn dùng foreignId() ở trên, dùng dropConstrainedForeignId sẽ tự động xử lý.
            // Trong trường hợp thông thường: $table->dropColumn('booking_id');
        });
    }
};
