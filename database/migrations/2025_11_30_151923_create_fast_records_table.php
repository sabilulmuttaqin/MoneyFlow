<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fast_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['expense', 'income']); // Pengeluaran / Pemasukan
            $table->string('category');                   // pakai string dulu, belum relasi
            $table->string('name');                       // nama pengeluaran / pemasukan
            $table->decimal('amount', 15, 2);             // nominal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fast_records');
    }
};