<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPesananTable extends Migration
{
    public function up()
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->string('pesanan_id', 10);
            $table->string('produk_id', 10);
            $table->string('katalog', 50)->nullable();
            $table->string('material', 50)->nullable();
            $table->string('warna', 50)->nullable();
            $table->text('ukuran_pendek')->nullable();
            $table->text('ukuran_panjang')->nullable();
            $table->string('file_desain', 255)->nullable();

            // Foreign key dengan cascade on delete
            $table->foreign('pesanan_id')
                ->references('id_pesanan')
                ->on('pesanan')
                ->onDelete('cascade');

            $table->foreign('produk_id')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('restrict'); // atau cascade jika diinginkan juga ikut terhapus
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_pesanan');
    }
}
