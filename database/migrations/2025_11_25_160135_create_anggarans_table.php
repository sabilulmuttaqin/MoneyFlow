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
            $table->string('kategori');  // Kebutuhan Pokok, Keinginan, Tabungan
            $table->decimal('prosentase', 5, 2);  // Misal: 50.00
            $table->decimal('nominal', 15, 2)->default(0);  // Uang yang dialokasikan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
}
