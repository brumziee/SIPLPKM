<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemilik;
use App\Models\Pegawai;

class PemilikPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Pemilik
        Pemilik::create([
            'Nama_Pemilik' => 'John Doe',
            'NoTelp_Pemilik' => '081234567890',
        ]);

        Pemilik::create([
            'Nama_Pemilik' => 'Jane Smith',
            'NoTelp_Pemilik' => '081234567891',
        ]);

        // Buat Pegawai
        Pegawai::create([
            'Nama_Pegawai' => 'Ahmad Subagja',
            'NoTelp_Pegawai' => '082345678901',
        ]);

        Pegawai::create([
            'Nama_Pegawai' => 'Siti Nurhaliza',
            'NoTelp_Pegawai' => '082345678902',
        ]);

        $this->command->info('Pemilik & Pegawai created successfully!');
    }
}