<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')
                ->nullable()
                ->constrained('transaksi')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->enum('aksi', ['tambah', 'edit', 'hapus']);
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Index
            $table->index('aksi');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_transaksi');
    }
};