<?php

namespace Database\Seeders;

use App\Models\Instruction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instruction::create([
            'id' => 1,
            'instruction_name'=>'Candidates are required to be present at the examination centre, THIRTY MINUTES before the time fixed for paper.',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 2,
            'instruction_name'=>'Candidates are forbidden from taking any material into the examination hall, that can be used for malpractice at the time of examination.',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 3,
            'instruction_name'=>'Candidates are requested to see the Notice Board at their place of examination regularly for changes if any, that may be notified later in the program.',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 4,
            'instruction_name'=>'No request for any special concession such as a change in time or any day fixed for the Autonomous College Examination on any ground shall be granted.',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 5,
            'instruction_name'=>'Use of non programmable calculator is permitted',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 6,
            'instruction_name'=>'All Student Should Note that there will be 02 Hrs. For Paper of 2 Credits, 02 Hrs. 30 Min. For Paper of 3 Credits, 03 Hrs. For Paper of 4 Credits (Ref.SPPU/Exam/Coordination/1472 Date - 24-10-19).',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 7,
            'instruction_name'=>'I:Internal E:External P:Practical Student should ensure that details like Name,Photo,PRN,Subjects printed on Hall Ticket are correct. Incase of any discrepancy, please immediately contact to college Examination Department.',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 2,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 8,
            'instruction_name'=>'In Case of any discrepancy between hallticket & time table published on college website (http://sangamnercollege.edu.in), the timetable on website to be followed.',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 2,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);

        Instruction::create([
            'id' => 9,
            'instruction_name'=>'* Their will be 02Hrs.for paper of 2 Credits,02Hrs.30Min. for paper of 3 Credits, 03Hrs.for paper of 4 Credits. (Ref.SPPU/exam/coordination/1472 Date-24-10-19)',
            'college_id' => 1,
            'user_id' => 1,
            'instructiontype_id' => 2,
            'is_active' => 1,
            'created_at' => '2023-08-28 10:03:01',
            'updated_at' => '2023-09-16 12:05:22'
        ]);
    }
}
