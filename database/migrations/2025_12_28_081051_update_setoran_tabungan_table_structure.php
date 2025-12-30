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
    Schema::table('setoran_tabungan', function (Blueprint $table) {

        // 1. Tambah user_id
        if (!Schema::hasColumn('setoran_tabungan', 'user_id')) {
            $table->unsignedBigInteger('user_id')->after('tabungan_id');
        }

        // 2. Tambah tipe transaksi
        if (!Schema::hasColumn('setoran_tabungan', 'tipe')) {
            $table->enum('tipe', ['setor', 'tarik'])->default('setor');
        }

        // 3. Tambah foreign key
        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('setoran_tabungan', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn(['user_id', 'tipe']);
    });
}

};
