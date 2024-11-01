<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian');
            $table->bigInteger('total_harga');
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->date('tgl_transaksi')->default(\Carbon\Carbon::now()->format('Y-m-d'));
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
        Schema::dropIfExists('pembelians');
    }
};
