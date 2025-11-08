<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            // Đổi sang kiểu TIME
            $table->time('gio_khoi_hanh')->change();
            $table->time('gio_den')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dateTime('gio_khoi_hanh')->change();
            $table->dateTime('gio_den')->nullable()->change();
        });
    }
};