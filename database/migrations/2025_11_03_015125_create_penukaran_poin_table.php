<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penukaran_poin', function (Blueprint $table) {
            $table->id('ID_Penukaran');
            $table->string('transaction_id', 50)->unique(); // Tambahan untuk tracking
            $table->unsignedBigInteger('ID_Pemilik');
            $table->unsignedBigInteger('ID_Pegawai');
            $table->unsignedBigInteger('ID_Pelanggan');
            $table->unsignedBigInteger('ID_Poin');
            $table->unsignedBigInteger('ID_Reward');
            $table->integer('Jumlah_Poin_Ditukar');
            $table->dateTime('Tanggal_Penukaran');
            $table->timestamps();

            // Foreign keys
            $table->foreign('ID_Pemilik')->references('ID_Pemilik')->on('pemilik')->onDelete('cascade');
            $table->foreign('ID_Pegawai')->references('ID_Pegawai')->on('pegawai')->onDelete('cascade');
            $table->foreign('ID_Pelanggan')->references('ID_Pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('ID_Poin')->references('ID_Poin')->on('poin_loyalitas')->onDelete('cascade');
            $table->foreign('ID_Reward')->references('ID_Reward')->on('reward')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penukaran_poin');
    }
};