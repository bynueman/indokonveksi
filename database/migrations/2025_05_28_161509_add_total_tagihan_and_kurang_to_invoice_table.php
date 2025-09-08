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
        $table->decimal('total_tagihan', 15, 2)->nullable()->after('diskon');
        $table->decimal('kurang', 15, 2)->nullable()->after('jumlah_dibayar');
    });
}

public function down()
{
    Schema::table('invoice', function (Blueprint $table) {
        $table->dropColumn(['total_tagihan', 'kurang']);
    });
}
};
