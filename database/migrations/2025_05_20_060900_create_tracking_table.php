<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/xxxx_xx_xx_xxxxxx_create_tracking_table.php
class CreateTrackingTable extends Migration
{
    public function up()
    {
Schema::create('tracking', function (Blueprint $table) {
    $table->string('id_tracking', 10)->primary();
    $table->string('pesanan_id', 10);
    $table->date('tanggal');
    $table->enum('status', ['beli bahan', 'dijahit', 'disablon/bordir', 'QC', 'packing', 'selesai']);
    $table->text('deskripsi')->nullable();

    $table->foreign('pesanan_id')->references('id_pesanan')->on('pesanan');
});

    }

    public function down()
    {
        Schema::dropIfExists('tracking');
    }
}

