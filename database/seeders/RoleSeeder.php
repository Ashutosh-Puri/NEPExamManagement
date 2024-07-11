<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => 1,
            'role_name' => 'Super Admin',
            'roletype_id' => 1,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 2,
            'role_name' => 'Admin',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 3,
            'role_name' => 'Principal',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 4,
            'role_name' => 'CEO',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 5,
            'role_name' => 'Head',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 6,
            'role_name' => 'Teacher',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 7,
            'role_name' => 'Co Ordinator',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 8,
            'role_name' => 'Supervisor',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 9,
            'role_name' => 'Assistant To Supervisor',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 10,
            'role_name' => 'Assistant CAP Director',
            'roletype_id' => 2,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 11,
            'role_name' => 'Admin Clerk',
            'roletype_id' => 3,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 12,
            'role_name' => 'CAP Clerk',
            'roletype_id' => 3,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 13,
            'role_name' => 'Clerk',
            'roletype_id' => 3,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);


        Role::create([
            'id' => 14,
            'role_name' => 'Admission Clerk',
            'roletype_id' => 3,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 15,
            'role_name' => 'Non Teaching',
            'roletype_id' => 3,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);

        Role::create([
            'id' => 16,
            'role_name' => 'General Clerk(Seating Arrangement)',
            'roletype_id' => 3,
            'college_id' => 1,
            'created_at' => '2021-05-20 00:02:57',
            'updated_at' => '2021-05-19 00:02:57'
        ]);
    }
}
