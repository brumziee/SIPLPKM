<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('ID_Transaksi');
            $table->unsignedBigInteger('ID_Pegawai');
            $table->unsignedBigInteger('ID_Pelanggan');
            $table->decimal('Jumlah_Transaksi', 15, 2);
            $table->dateTime('Tanggal_Transaksi');
            $table->timestamps();

            // Foreign keys
            $table->foreign('ID_Pegawai')->references('ID_Pegawai')->on('pegawai')->onDelete('cascade');
            $table->foreign('ID_Pelanggan')->references('ID_Pelanggan')->on('pelanggan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};