<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Đổi kiểu dữ liệu từ DATETIME sang TIME
        DB::statement('ALTER TABLE trips MODIFY gio_khoi_hanh TIME');
        DB::statement('ALTER TABLE trips MODIFY gio_den TIME NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE trips MODIFY gio_khoi_hanh DATETIME');
        DB::statement('ALTER TABLE trips MODIFY gio_den DATETIME NULL');
    }
};