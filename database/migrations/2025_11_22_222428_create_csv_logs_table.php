<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csv_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->integer('imported_rows')->default(0);
            $table->json('errors')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

            // Optional: kalau mau relasi user
            $table->foreign('uploaded_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csv_logs');
    }
};
