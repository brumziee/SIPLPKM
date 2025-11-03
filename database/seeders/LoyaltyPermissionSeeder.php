<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LoyaltyPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Pelanggan (Customer) permissions - SUDAH ADA, tapi pastikan
            'customer.view',
            'customer.create', 
            'customer.store',
            'customer.edit',
            'customer.update',
            'customer.delete',
            
            // Reward permissions (menggunakan nama product untuk backward compatibility)
            'product.view',
            'product.create',
            'product.store',
            'product.edit',
            'product.update',
            'product.delete',
            
            // Pemilik permissions
            'pemilik.view',
            'pemilik.create',
            'pemilik.store',
            'pemilik.edit',
            'pemilik.update',
            'pemilik.delete',
            
            // Pegawai permissions
            'pegawai.view',
            'pegawai.create',
            'pegawai.store',
            'pegawai.edit',
            'pegawai.update',
            'pegawai.delete',
            
            // Transaksi permissions
            'transaksi.view',
            'transaksi.create',
            'transaksi.store',
            'transaksi.edit',
            'transaksi.update',
            'transaksi.delete',
            
            // Poin Loyalitas permissions
            'poin.view',
            'poin.add',
            
            // Penukaran Poin permissions
            'penukaran.view',
            'penukaran.create',
            'penukaran.store',
            'penukaran.delete',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Give all permissions to admin
        $adminRole->syncPermissions(Permission::all());

        $this->command->info('Loyalty permissions created successfully!');
    }
}