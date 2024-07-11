<?php

namespace Database\Seeders;

use App\Models\Paperset;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PapersetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paperset::create([
            'id' => 1,
            'set_name' => 'A',
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Paperset::create([
            'id' => 2,
            'set_name' => 'B',
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Paperset::create([
            'id' => 3,
            'set_name' => 'C',
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);
    }
}
