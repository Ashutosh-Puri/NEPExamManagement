<?php

namespace Database\Seeders;

use App\Models\Instructiontype;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InstructiontypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instructiontype::create([
            'id' => 1,
            'instruction_type'=>'Exam Time Table',
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instructiontype::create([
            'id' => 2,
            'instruction_type'=>'Hall Ticket',
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);
    }
}
