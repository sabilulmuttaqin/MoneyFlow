<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('setoran_tabungan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tabungan_id');
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();

            $table->foreign('tabungan_id')->references('id')->on('tabungan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran_tabungan');
    }
};
