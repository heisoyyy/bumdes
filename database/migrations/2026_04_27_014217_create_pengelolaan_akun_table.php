<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengelolaan_akun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengurus_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreignId('masyarakat_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->enum('aksi', [
                'tambah', 
                'edit', 
                'nonaktifkan', 
                'aktifkan'
            ]);
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengelolaan_akun');
    }
};