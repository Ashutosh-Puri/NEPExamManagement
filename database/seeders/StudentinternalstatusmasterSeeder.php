<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studentinternalstatusmaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentinternalstatusmasterSeeder extends Seeder
{
    public function run(): void
    {
        Studentinternalstatusmaster::create( [
            'name'=>'Present',
            'short_name'=>'P',
            'is_active'=>'0',
            'created_at'=>'2024-04-27 09:13:58',
            'updated_at'=>'2024-04-27 09:13:58',
        ] );

        Studentinternalstatusmaster::create( [
            'name'=>'Absent',
            'short_name'=>'A',
            'is_active'=>'0',
            'created_at'=>'2024-04-27 09:13:58',
            'updated_at'=>'2024-04-27 09:13:58',
        ] );

        Studentinternalstatusmaster::create( [
            'name'=>'Copy-Case',
            'short_name'=>'CC',
            'is_active'=>'0',
            'created_at'=>'2024-04-27 09:13:58',
            'updated_at'=>'2024-04-27 09:13:58',
        ] );
    }
}
