<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poin_loyalitas', function (Blueprint $table) {
            $table->id('ID_Poin');
            $table->unsignedBigInteger('ID_Pelanggan')->unique(); // UNIQUE karena One-to-One
            $table->integer('Jumlah_Poin')->default(0);
            $table->timestamps();

            // Foreign key
            $table->foreign('ID_Pelanggan')
                ->references('ID_Pelanggan')
                ->on('pelanggan')
                ->onDelete('cascade'); // Jika pelanggan dihapus, poin juga terhapus
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poin_loyalitas');
    }
};