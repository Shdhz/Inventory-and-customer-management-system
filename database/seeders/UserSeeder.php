<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        $produksiRole = Role::firstOrCreate(['name' => 'produksi']);

        // make admin role dummy data
        $admin = User::updateOrCreate(
            ['username'=>'dhafa'], 
            [
                'name'=>'admin dhafa',
                'password'=>bcrypt('admin')
            ]);
        $admin->assignRole($adminRole);

        // make SUpervisor role dummy data
        $admin = User::updateOrCreate(
            ['username'=>'corry'], 
            [
                'name'=>'supervisor corry',
                'password'=>bcrypt('supervisor')
            ]);
        $admin->assignRole($supervisorRole);

        // make Production role dummy data
        $admin = User::updateOrCreate(
            ['username'=>'iqbal'], 
            [
                'name'=>'produksi ikbal',
                'password'=>bcrypt('ikbal')
            ]);
        $admin->assignRole($produksiRole);
    }
}
