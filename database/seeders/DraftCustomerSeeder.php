<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DraftCustomer;
use App\Models\User;
use Faker\Factory as Faker;

class DraftCustomerSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua users
        $users = User::all();

        // Jika tidak ada pengguna, tampilkan pesan dan hentikan seeding
        if ($users->isEmpty()) {
            $this->command->info('Tidak ada data di tabel users. Seeder untuk draft_customer tidak dapat dijalankan.');
            return;
        }

        // Generate data menggunakan Faker
        $faker = Faker::create();

        foreach ($users as $user) {
            DraftCustomer::updateOrCreate(
                ['user_id' => $user->id], // Kondisi pencarian berdasarkan user_id
                [
                    'Nama' => $faker->name,
                    'no_hp' => $faker->phoneNumber,
                    'email' => $faker->email,
                    'provinsi' => $faker->state,
                    'kota' => $faker->city,
                    'alamat_lengkap' => $faker->address,
                    'sumber' => $faker->word,
                ]
            );
        }
    }
}

