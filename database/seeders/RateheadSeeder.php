<?php

namespace Database\Seeders;

use App\Models\Ratehead;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class RateheadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 200; $i++) {
            Ratehead::create([
                'headname' => $faker->word,
                'type' => $faker->regexify('[A-Za-z]{1,2}'),
                'status' => $faker->numberBetween(1, 0),
            ]);
        }
    }
}
