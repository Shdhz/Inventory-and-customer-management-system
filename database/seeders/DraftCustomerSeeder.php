<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DraftCustomer;
use Faker\Factory as Faker;

class DraftCustomerSeeder extends Seeder
{
    public function run()
    {
        // ID pengguna default untuk dummy data
        $defaultUserId = 1;

        // Pastikan pengguna dengan ID ini ada di tabel users
        if (!\App\Models\User::where('id', $defaultUserId)->exists()) {
            $this->command->info("User dengan ID $defaultUserId tidak ditemukan. Harap tambahkan user dengan ID tersebut sebelum menjalankan seeder ini.");
            return;
        }

        // Jumlah data dummy yang ingin dibuat
        $jumlahData = 15;

        // Generate data menggunakan Faker
        $faker = Faker::create();

        // Loop untuk membuat data dummy
        for ($i = 0; $i < $jumlahData; $i++) {
            DraftCustomer::create([
                'user_id' => $defaultUserId, // Gunakan ID pengguna default
                'Nama' => $faker->name,
                'no_hp' => substr( $faker->phoneNumber, 0, 15),
                'email' => $faker->unique()->safeEmail,
                'provinsi' => $faker->state,
                'kota' => $faker->city,
                'alamat_lengkap' => $faker->address,
                'sumber' => $faker->word,
            ]);
        }

        $this->command->info("$jumlahData data DraftCustomer berhasil dibuat dengan user_id default $defaultUserId.");
    }
}
