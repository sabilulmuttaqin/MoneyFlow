<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke anggaran
            // $table->unsignedBigInteger('anggaran_id');
            // $table->foreign('anggaran_id')
            //       ->references('id')
            //       ->on('anggarans')
            //       ->onDelete('cascade');

            $table->string('name');
            $table->string('icon')->nullable();
            $table->integer('budget')->default(0);
            $table->integer('used')->default(0);
            $table->enum('type', ['pengeluaran', 'pemasukan'])->default('pengeluaran');
            $table->enum('status', ['kebutuhan pokok', 'keinginan', 'tabungan'])->default('kebutuhan pokok');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('categories');
    }
};
