<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use App\Models\Examfeemaster;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExamFeeMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Examfeemaster::create([
            'id' => 1,
            'fee_name' => 'Form Fee',
            'remark' => null,
            'default_professional_fee' => 30,
            'default_non_professional_fee' => 20,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 2,
            'fee_name' => 'Exam Fee',
            'remark' => null,
            'default_professional_fee' => 1200,
            'default_non_professional_fee' => 800,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 3,
            'fee_name' => 'CAP Fee',
            'remark' => null,
            'default_professional_fee' => 145,
            'default_non_professional_fee' => 85,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 4,
            'fee_name' => 'Statement of Marks Fee',
            'remark' => null,
            'default_professional_fee' => 145,
            'default_non_professional_fee' => 85,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 5,
            'fee_name' => 'Passing Certificate Fee',
            'remark' => null,
            'default_professional_fee' => 145,
            'default_non_professional_fee' => 85,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 6,
            'fee_name' => 'Project Fee/Dissertation',
            'remark' => null,
            'default_professional_fee' => null,
            'default_non_professional_fee' => null,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 7,
            'fee_name' => 'EVS Fee',
            'remark' => null,
            'default_professional_fee' => null,
            'default_non_professional_fee' => null,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 8,
            'fee_name' => 'Internal Marks Fee',
            'remark' => null,
            'default_professional_fee' => 20,
            'default_non_professional_fee' => 20,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 9,
            'fee_name' => 'Departmental Fee',
            'remark' => null,
            'default_professional_fee' => 0,
            'default_non_professional_fee' => 0,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 10,
            'fee_name' => 'Transcript Fee',
            'remark' => null,
            'default_professional_fee' => 0,
            'default_non_professional_fee' => 0,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 11,
            'fee_name' => 'Late Fee',
            'remark' => null,
            'default_professional_fee' => 150,
            'default_non_professional_fee' => 150,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 12,
            'fee_name' => 'Fine Fee',
            'remark' => null,
            'default_professional_fee' => 1000,
            'default_non_professional_fee' => 1000,
            'form_type_id' => 1,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 13,
            'fee_name' => 'Backlog Fee',
            'remark' => null,
            'default_professional_fee' => null,
            'default_non_professional_fee' => null,
            'form_type_id' => 4,
            'apply_fee_id' => 1,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 14,
            'fee_name' => 'Photocopy Form Fee',
            'remark' => null,
            'default_professional_fee' => null,
            'default_non_professional_fee' => null,
            'form_type_id' => 2,
            'apply_fee_id' => 2,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 15,
            'fee_name' => 'Photocopy Fee',
            'remark' => null,
            'default_professional_fee' => null,
            'default_non_professional_fee' => null,
            'form_type_id' => 2,
            'apply_fee_id' => 2,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 16,
            'fee_name' => 'Revaluation Form Fee',
            'remark' => null,
            'default_professional_fee' => null,
            'default_non_professional_fee' => null,
            'form_type_id' => 3,
            'apply_fee_id' => 3,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 17,
            'fee_name' => 'Revaluation Subject Fee',
            'remark' => null,
            'default_professional_fee' => null,
            'default_non_professional_fee' => null,
            'form_type_id' => 3,
            'apply_fee_id' => 3,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

        Examfeemaster::create([
            'id' => 18,
            'fee_name' => 'Revaluation Fine Fee',
            'remark' => null,
            'default_professional_fee' => 1000,
            'default_non_professional_fee' => 1000,
            'form_type_id' => 3,
            'apply_fee_id' => 3,
            'active_status' => 1,
            'approve_status' => 0,
        ]);

    }
}
