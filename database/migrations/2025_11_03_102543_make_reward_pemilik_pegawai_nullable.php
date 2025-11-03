<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reward', function (Blueprint $table) {
            // Drop foreign key constraints dulu
            $table->dropForeign(['ID_Pemilik']);
            $table->dropForeign(['ID_Pegawai']);
            
            // Ubah kolom jadi nullable
            $table->unsignedBigInteger('ID_Pemilik')->nullable()->change();
            $table->unsignedBigInteger('ID_Pegawai')->nullable()->change();
            
            // Tambah foreign key lagi dengan nullable
            $table->foreign('ID_Pemilik')->references('ID_Pemilik')->on('pemilik')->onDelete('set null');
            $table->foreign('ID_Pegawai')->references('ID_Pegawai')->on('pegawai')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('reward', function (Blueprint $table) {
            $table->dropForeign(['ID_Pemilik']);
            $table->dropForeign(['ID_Pegawai']);
            
            $table->unsignedBigInteger('ID_Pemilik')->nullable(false)->change();
            $table->unsignedBigInteger('ID_Pegawai')->nullable(false)->change();
            
            $table->foreign('ID_Pemilik')->references('ID_Pemilik')->on('pemilik')->onDelete('cascade');
            $table->foreign('ID_Pegawai')->references('ID_Pegawai')->on('pegawai')->onDelete('cascade');
        });
    }
};