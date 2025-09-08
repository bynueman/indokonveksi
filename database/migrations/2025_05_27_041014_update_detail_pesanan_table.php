<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up()
{
    Schema::table('detail_pesanan', function (Blueprint $table) {
        // Jangan drop ukuran_pendek/panjang karena memang sudah tidak ada
        $table->enum('tipe_lengan', ['pendek', 'panjang'])->nullable()->after('warna');
        $table->json('jumlah_ukuran')->nullable()->after('tipe_lengan');
    });
}


    public function down()
    {
        Schema::table('detail_pesanan', function (Blueprint $table) {
            // Revert ke kondisi awal
            $table->dropColumn(['tipe_lengan', 'ukuran_opsional']);

            $table->text('ukuran_pendek')->nullable();
            $table->text('ukuran_panjang')->nullable();
        });
    }
};
