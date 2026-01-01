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
        Schema::table('anggarans', function (Blueprint $table) {
            $table->string('bulan', 7)->after('user_id'); 
            // format: YYYY-MM (contoh: 2025-12)
        });
    }

    public function down()
    {
        Schema::table('anggarans', function (Blueprint $table) {
            $table->dropColumn('bulan');
        });
    }

};
