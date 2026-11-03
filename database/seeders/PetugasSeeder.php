<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($index = 0; $index < 5; $index++) {
            User::updateOrCreate(
                ['email' => \sprintf('petugas%02d@bpbd-bandung.go.id', $index + 1)],
                [
                    'name' => $faker->name(),
                    'password' => Hash::make('password'),
                ]
            );
        }

        User::updateOrCreate(
            ['email' => 'admin@bpbd-bandung.go.id'],
            [
                'name' => 'Admin BPBD Kabupaten Bandung',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}