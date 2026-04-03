<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesananTable extends Migration
{
    public function up()
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('id_pesanan')->primary(); // pakai string ID manual (seperti PSN001)
            $table->string('pelanggan_id', 10);
            $table->string('nama_pesanan', 100);
            $table->date('tanggal_pesanan');
            $table->date('deadline');
            $table->enum('status', ['diproses', 'selesai', 'dibatalkan'])->default('diproses');
            $table->text('catatan')->nullable();

            $table->foreign('pelanggan_id')->references('id_pelanggan')->on('pelanggan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesanan'); // yang benar adalah drop pesanan, bukan detail_pesanan
    }
}
