<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemilik', function (Blueprint $table) {
            $table->id('ID_Pemilik');
            $table->string('Nama_Pemilik');
            $table->string('NoTelp_Pemilik', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemilik');
    }
};