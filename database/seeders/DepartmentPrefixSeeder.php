<?php

namespace Database\Seeders;

use App\Models\Departmentprefix;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentPrefixSeeder extends Seeder
{
    public function run(): void
    {
        Departmentprefix::create([
            'dept_id' => 1,
            'pattern_id' => 3,
            'prefix' => 'DCS',
            'postfix' => 'S',
            'status' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Departmentprefix::create([
            'dept_id' => 12,
            'pattern_id' => 2,
            'prefix' => 'MAR',
            'postfix' => 'M',
            'status' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        Departmentprefix::create([
            'dept_id' => 11,
            'pattern_id' => 2,
            'prefix' => 'COM',
            'postfix' => 'C',
            'status' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
    }
}