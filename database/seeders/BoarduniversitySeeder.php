<?php

namespace Database\Seeders;

use App\Models\Boarduniversity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoarduniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Boarduniversity::create([
            'id' => 1,
            'boarduniversity_name' => 'Maharashtra State Board ',
            'is_active' => 1,
            'created_at' => '2023-10-01 10:17:33',
            'updated_at' => '2023-10-01 10:17:33'
        ]);



        Boarduniversity::create([
            'id' => 2,
            'boarduniversity_name' => 'Central Board of Secondary Education (CBSE)',
            'is_active' => 1,
            'created_at' => '2023-10-01 10:17:33',
            'updated_at' => '2023-10-01 10:17:33'
        ]);



        Boarduniversity::create([
            'id' => 3,
            'boarduniversity_name' => 'Indian Certificate of Secondary Education(ICSE)',
            'is_active' => 1,
            'created_at' => '2023-10-01 10:17:33',
            'updated_at' => '2023-10-01 10:17:33'
        ]);



        Boarduniversity::create([
            'id' => 4,
            'boarduniversity_name' => 'Other',
            'is_active' => 1,
            'created_at' => '2023-10-01 10:17:33',
            'updated_at' => '2023-10-01 10:17:33'
        ]);


    }
}
