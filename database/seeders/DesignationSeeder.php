<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        Designation::create([
            'designation_name'=>'super admin',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'admin',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'clerk',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'coe',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'admissionclerk',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Principal and Head',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Assistant Professor ',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Assistant Professor and Head',
            'status' => 1,
            'created_at'=>'2021-05-14 07:06:54',
            'updated_at'=>'2021-05-13 07:06:54'
        ]);

        Designation::create([
            'designation_name'=>'Associate Professor and Head',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Associate Professor ',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Assistant Professor and Head',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Professor',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Professor and Head',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create( [
            'designation_name'=>'Marketing Officer',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ] );

        Designation::create([
            'designation_name'=>'General Clerk(Seating Arrangement)',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'CAP Director',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Assistant CAP Director',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'CAP Clerk',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);

        Designation::create([
            'designation_name'=>'Assistant to Supervisor',
            'status' => 1,
            'created_at'=>'2021-05-20 00:02:57',
            'updated_at'=>'2021-05-19 00:02:57'
        ]);
    }
}