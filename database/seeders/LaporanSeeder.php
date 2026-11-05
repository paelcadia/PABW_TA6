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
            $tinggiAir = $faker->numberBetween(10, 150);
            $tanggal = $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');

            Laporan::create([
                'nama_pelapor' => $faker->name(),
                'no_hp' => $faker->numerify('08##########'),
                'lokasi' => $faker->streetAddress(),
                'kecamatan' => $faker->randomElement($kecamatan),
                'tinggi_air' => $tinggiAir,
                'tinggi_genangan' => $tinggiAir,
                'keterangan' => $faker->sentence(),
                'status' => $faker->randomElement(['Menunggu', 'Diproses', 'Selesai']),
                'tanggal_kejadian' => $tanggal,
            ]);
        }
    }
}
