<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin default
        User::updateOrCreate(
            ['email' => 'admin@kampus.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Contoh 1 akun dosen wali untuk testing
        $userDosen = User::updateOrCreate(
            ['email' => 'dosen@kampus.ac.id'],
            [
                'name' => 'Dr. Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        $dosen = Dosen::updateOrCreate(
            ['nip' => '198001012005011001'],
            [
                'user_id' => $userDosen->id,
                'nama' => 'Dr. Budi Santoso',
                'email' => 'dosen@kampus.ac.id',
                'no_hp' => '081234567890',
            ]
        );

        // Contoh 1 akun mahasiswa untuk testing
        $userMhs = User::updateOrCreate(
            ['email' => 'mahasiswa@kampus.ac.id'],
            [
                'name' => 'Andi Wijaya',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );

        Mahasiswa::updateOrCreate(
            ['nim' => '2101010001'],
            [
                'user_id' => $userMhs->id,
                'nama' => 'Andi Wijaya',
                'angkatan' => '2021',
                'program_studi' => 'Teknik Informatika',
                'dosen_id' => $dosen->id,
                'email' => 'mahasiswa@kampus.ac.id',
                'no_hp' => '081298765432',
            ]
        );
    }
}
