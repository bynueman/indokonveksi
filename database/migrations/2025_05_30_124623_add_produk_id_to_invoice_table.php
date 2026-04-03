<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->string('produk_id', 10)->after('pesanan_id')->nullable();

            $table->foreign('produk_id')
                ->references('id_produk')
                ->on('produk')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->dropColumn('produk_id');
        });
    }
};
