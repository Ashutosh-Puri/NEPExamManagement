<?php

namespace Database\Seeders;

use App\Models\Programme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Programme::create([
            'id' => 1,
            'programme_name' => 'Arts',
            'active' => 1,
            'created_at' => '2023-09-05 23:37:01',
            'updated_at' => '2023-09-05 23:37:01'
        ]);

        Programme::create([
            'id' => 2,
            'programme_name' => 'Commerce',
            'active' => 1,
            'created_at' => '2023-09-05 23:37:01',
            'updated_at' => '2023-09-05 23:37:01'
        ]);

        Programme::create([
            'id' => 3,
            'programme_name' => 'Science',
            'active' => 1,
            'created_at' => '2023-09-05 23:37:01',
            'updated_at' => '2023-09-05 23:37:01'
        ]);

        Programme::create([
            'id' => 4,
            'programme_name' => 'Vocation',
            'active' => 1,
            'created_at' => '2023-09-05 23:37:01',
            'updated_at' => '2023-09-05 23:37:01'
        ]);

        Programme::create([
            'id' => 5,
            'programme_name' => 'Other',
            'active' => 1,
            'created_at' => '2023-09-05 23:37:01',
            'updated_at' => '2023-09-05 23:37:01'
        ]);
    }
}
