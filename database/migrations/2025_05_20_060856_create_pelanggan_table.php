<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// database/migrations/xxxx_xx_xx_xxxxxx_create_pelanggan_table.php
class CreatePelangganTable extends Migration
{
    public function up()
    {
Schema::create('pelanggan', function (Blueprint $table) {
    $table->string('id_pelanggan', 10)->primary();
    $table->string('nama', 100);
    $table->string('no_hp', 20);
    $table->text('alamat');
    $table->timestamps(); // ini akan otomatis buat 'created_at' dan 'updated_at'
});

    }

    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
}
