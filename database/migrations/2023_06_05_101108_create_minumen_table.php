<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('minumen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_minuman');
            $table->string('nama_minuman');
            $table->string('deskripsi');
            $table->string('gambar');
            $table->bigInteger('harga');
            $table->foreignId('user_id');
            $table->foreignId('cabang_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minumen');
    }
};
