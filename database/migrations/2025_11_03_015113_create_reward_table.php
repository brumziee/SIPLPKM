<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward', function (Blueprint $table) {
            $table->id('ID_Reward');
            $table->unsignedBigInteger('ID_Pemilik')->nullable();
            $table->unsignedBigInteger('ID_Pegawai')->nullable();
            $table->string('Nama_Reward');
            $table->integer('Poin_Dibutuhkan');
            $table->timestamps();

            // Foreign keys
            $table->foreign('ID_Pemilik')->references('ID_Pemilik')->on('pemilik')->onDelete('cascade');
            $table->foreign('ID_Pegawai')->references('ID_Pegawai')->on('pegawai')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward');
    }
};