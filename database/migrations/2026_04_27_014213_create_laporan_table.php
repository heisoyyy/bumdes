<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->tinyInteger('periode_bulan')
                ->comment('Bulan 1-12');
            $table->year('periode_tahun');
            $table->string('file_pdf', 255)->nullable();
            $table->string('file_excel', 255)->nullable();
            $table->timestamps();

            // Index
            $table->index(['periode_bulan', 'periode_tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};