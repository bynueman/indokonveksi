<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/xxxx_xx_xx_xxxxxx_create_invoice_table.php
class CreateInvoiceTable extends Migration
{
    public function up()
    {
Schema::create('invoice', function (Blueprint $table) {
    $table->string('id_invoice', 10)->primary();
    $table->string('pesanan_id', 10);
    $table->string('pelanggan_id', 10);
    $table->date('tanggal_invoice');
    $table->string('npwp', 30)->nullable();
    $table->integer('jumlah');
    $table->decimal('harga_satuan', 15, 2);
    $table->decimal('biaya_tambahan', 15, 2)->nullable();
    $table->decimal('diskon', 15, 2)->nullable();
    $table->decimal('total_tagihan', 15, 2);
    $table->decimal('jumlah_dibayar', 15, 2);
    $table->decimal('kurang', 15, 2);
    $table->enum('status_pembayaran', ['dp', 'lunas'])->default('dp');
    $table->string('metode_pembayaran', 100)->nullable();
    $table->text('keterangan')->nullable();

    $table->foreign('pesanan_id')->references('id_pesanan')->on('pesanan');
    $table->foreign('pelanggan_id')->references('id_pelanggan')->on('pelanggan');
});

    }

    public function down()
    {
        Schema::dropIfExists('invoice');
    }
}

