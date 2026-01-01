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
    Schema::table('tabungan', function (Blueprint $table) {

        // 1. Hapus kolom redundan
        if (Schema::hasColumn('tabungan', 'total_setoran')) {
            $table->dropColumn('total_setoran');
        }

        // 2. Pastikan setoran_awal punya default
        $table->decimal('setoran_awal', 15, 2)->default(0)->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('tabungan', function (Blueprint $table) {
        $table->decimal('total_setoran', 15, 2)->default(0);
    });
}

};
