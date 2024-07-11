<?php

namespace Database\Seeders;

use App\Models\Pattern;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pattern::create([
            'id' => 1,
            'pattern_name' => '2013 Credit Pattern',
            'pattern_startyear' => '2013',
            'pattern_valid' => 'April 2018',
            'status' => '0',
            'college_id' => '1',
            'created_at' => '31-05-2021  05:45:57',
            'updated_at' => '02-06-2021  06:17:25'
        ]);

        Pattern::create([
            'id' => 2,
            'pattern_name' => '2020 CREDIT PATTERN',
            'pattern_startyear' => '2020',
            'pattern_valid' => 'April 2025',
            'status' => '1',
            'college_id' => '1',
            'created_at' => '31-05-2021  05:46:11',
            'updated_at' => '31-05-2021  05:46:11'
        ]);

        Pattern::create([
            'id' => 3,
            'pattern_name' => 'NEP 2023 CREDIT PATTERN',
            'pattern_startyear' => '2023',
            'pattern_valid' => 'April 2030',
            'status' => '1',
            'college_id' => '1',
            'created_at' => '02-10-2023  05:06:37',
            'updated_at' => '02-10-2023  05:06:37'
        ]);


    }
}
