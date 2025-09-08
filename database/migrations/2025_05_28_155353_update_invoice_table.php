<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('invoice', function (Blueprint $table) {
        $table->dropColumn(['jumlah', 'harga_satuan', 'total_tagihan', 'kurang']); // Hapus kolom redundant
        // Tambah kolom baru jika perlu, misalnya:
        // $table->string('status_tagihan')->nullable();
    });
}

public function down()
{
    Schema::table('invoice', function (Blueprint $table) {
        $table->integer('jumlah');
        $table->decimal('harga_satuan', 15, 2);
        $table->decimal('total_tagihan', 15, 2);
        $table->decimal('kurang', 15, 2);
    });
}
};
