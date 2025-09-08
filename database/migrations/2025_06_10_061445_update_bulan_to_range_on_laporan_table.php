<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBulanToRangeOnLaporanTable extends Migration
{
    public function up()
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn('bulan');
            $table->date('tanggal_mulai')->after('jenis_laporan');
            $table->date('tanggal_selesai')->after('tanggal_mulai');
        });
    }

    public function down()
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->date('bulan')->after('jenis_laporan');
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
}
