<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pengurus BUMDes
        User::create([
            'name'      => 'Admin Pengurus BUMDes',
            'username'  => 'pengurus',
            'email'     => 'pengurus@bumdes-kampar.com',
            'password'  => Hash::make('password123'),
            'role'      => 'pengurus',
            'no_hp'     => '08100000001',
            'is_active' => 'aktif',
        ]);

        // Kepala Desa
        User::create([
            'name'      => 'Kepala Desa Kampar',
            'username'  => 'kepaladesa',
            'email'     => 'kepaladesa@bumdes-kampar.com',
            'password'  => Hash::make('password123'),
            'role'      => 'kepala_desa',
            'no_hp'     => '08100000002',
            'is_active' => 'aktif',
        ]);

        // Masyarakat contoh
        User::create([
            'name'      => 'Budi Santoso',
            'username'  => 'budi',
            'email'     => 'budi@gmail.com',
            'password'  => Hash::make('password123'),
            'role'      => 'masyarakat',
            'no_hp'     => '08100000003',
            'is_active' => 'aktif',
        ]);
    }
}