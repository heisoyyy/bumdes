<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['pengurus', 'kepala_desa', 'masyarakat']);
            $table->string('no_hp', 15)->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->enum('is_active', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};