<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/xxxx_xx_xx_xxxxxx_create_produk_table.php
class CreateProdukTable extends Migration
{
    public function up()
    {
Schema::create('produk', function (Blueprint $table) {
    $table->string('id_produk', 10)->primary();
    $table->string('nama_produk', 100);
    $table->decimal('harga', 15, 2);
    $table->timestamps(); // ini akan otomatis buat 'created_at' dan 'updated_at'
});

    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
}

