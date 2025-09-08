<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanTable extends Migration
{
    public function up()
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id(); // Menambahkan kolom id
            $table->string('id_laporan')->unique(); // ID laporan unik
            $table->enum('jenis_laporan', ['Omzet', 'Laba', 'Produk Terlaris']);
            $table->date('bulan');
            $table->text('deskripsi')->nullable();
            $table->integer('total_pesanan')->nullable();
            $table->timestamps(); // Untuk menambah created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('laporan');
    }
}
