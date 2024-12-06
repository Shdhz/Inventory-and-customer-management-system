<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Definisikan izin
        $permissions = [
            'view stock',
            'add stock',
            'edit stock',
            'delete stock',
        ];

        // Buat atau ambil izin yang sudah ada
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Buat atau ambil peran 'admin' dan berikan izin 'view stock'
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo('view stock');

        // Buat atau ambil peran 'produksi' dan berikan semua izin terkait stok
        $produksiRole = Role::firstOrCreate(['name' => 'produksi']);
        $produksiRole->givePermissionTo(['view stock', 'add stock', 'edit stock', 'delete stock']);
    }
}
