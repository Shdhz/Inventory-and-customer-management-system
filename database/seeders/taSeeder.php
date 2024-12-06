<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class taSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // make admin role dummy data
        $admin = User::updateOrCreate(
            ['username' => 'atila'],
            [
                'name' => 'admin atila',
                'password' => bcrypt('atila')
            ]
        );
        $admin->assignRole($adminRole);
    }
}
