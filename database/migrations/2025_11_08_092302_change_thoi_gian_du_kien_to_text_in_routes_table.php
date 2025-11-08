<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('thoi_gian_du_kien', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->time('thoi_gian_du_kien')->change();
        });
    }
};