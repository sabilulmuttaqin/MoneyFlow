<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggaransTable extends Migration
{
    public function up(): void
    {
        Schema::create('anggarans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Tambahkan user_id
        $table->decimal('kebutuhan_pokok', 15, 0)->default(0); // Nominal Kebutuhan Pokok
        $table->decimal('keinginan', 15, 0)->default(0);      // Nominal Keinginan
        $table->decimal('tabungan', 15, 0)->default(0);       // Nominal Tabungan
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
}
