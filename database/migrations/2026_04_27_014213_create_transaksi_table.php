<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 20)->unique();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreignId('kategori_id')
                ->constrained('kategori_transaksi')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('nominal', 15, 2);
            $table->decimal('saldo_setelah', 15, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('bukti_transaksi', 255)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            // Index
            $table->index('tanggal');
            $table->index('jenis');
            $table->index('deleted_at');
            $table->index('kode_transaksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};