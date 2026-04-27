<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backup_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->string('nama_file', 255);
            $table->string('ukuran_file', 50);
            $table->enum('tipe_backup', ['manual', 'otomatis'])
                ->default('manual');
            $table->enum('status', ['berhasil', 'gagal'])
                ->default('berhasil');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backup_data');
    }
};