<?php

namespace Database\Seeders;

use App\Models\Laporan;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $kecamatan = ['Dayeuhkolot', 'Baleendah', 'Bojongsoang', 'Rancaekek', 'Majalaya'];

        for ($index = 0; $index < 15; $index++) {
            Laporan::create([
                'nama_pelapor' => $faker->name(),
                'lokasi' => $faker->randomElement($kecamatan),
                'tinggi_genangan' => $faker->numberBetween(10, 150),
                'tanggal_kejadian' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            ]);
        }
    }
}