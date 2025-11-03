<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        Reward::create([
            'ID_Pemilik' => 1,
            'ID_Pegawai' => 1,
            'Nama_Reward' => 'Diskon 10%',
            'Poin_Dibutuhkan' => 50,
        ]);

        Reward::create([
            'ID_Pemilik' => 1,
            'ID_Pegawai' => 1,
            'Nama_Reward' => 'Gratis 1 Item',
            'Poin_Dibutuhkan' => 100,
        ]);

        Reward::create([
            'ID_Pemilik' => 1,
            'ID_Pegawai' => 2,
            'Nama_Reward' => 'Voucher 50k',
            'Poin_Dibutuhkan' => 150,
        ]);

        Reward::create([
            'ID_Pemilik' => 2,
            'ID_Pegawai' => 2,
            'Nama_Reward' => 'Diskon 20%',
            'Poin_Dibutuhkan' => 200,
        ]);

        Reward::create([
            'ID_Pemilik' => 2,
            'ID_Pegawai' => 1,
            'Nama_Reward' => 'Free Delivery',
            'Poin_Dibutuhkan' => 75,
        ]);

        $this->command->info('Reward created successfully!');
    }
}