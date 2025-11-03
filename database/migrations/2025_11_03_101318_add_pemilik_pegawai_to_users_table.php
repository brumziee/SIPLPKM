<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_Pemilik')->nullable()->after('id');
            $table->unsignedBigInteger('ID_Pegawai')->nullable()->after('ID_Pemilik');
            
            $table->foreign('ID_Pemilik')->references('ID_Pemilik')->on('pemilik')->onDelete('set null');
            $table->foreign('ID_Pegawai')->references('ID_Pegawai')->on('pegawai')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ID_Pemilik']);
            $table->dropForeign(['ID_Pegawai']);
            $table->dropColumn(['ID_Pemilik', 'ID_Pegawai']);
        });
    }
};