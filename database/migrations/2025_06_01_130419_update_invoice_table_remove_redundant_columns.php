<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->decimal('jumlah_bayar', 15, 2)->nullable()->after('diskon');
            $table->decimal('total_tagihan', 15, 2)->nullable()->change();
            $table->decimal('kurang', 15, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn('jumlah_bayar');
        });
    }
};