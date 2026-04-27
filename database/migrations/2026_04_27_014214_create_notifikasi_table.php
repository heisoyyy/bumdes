<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('judul', 255);
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Index
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};