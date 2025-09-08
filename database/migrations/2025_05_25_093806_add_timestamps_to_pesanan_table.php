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
    Schema::table('pesanan', function (Blueprint $table) {
        $table->timestamps(); // ini menambahkan created_at & updated_at
    });
}

    public function down()
{
    Schema::table('pesanan', function (Blueprint $table) {
        $table->dropTimestamps();
    });
}

};
