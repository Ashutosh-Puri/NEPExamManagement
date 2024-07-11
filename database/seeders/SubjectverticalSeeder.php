<?php

namespace Database\Seeders;

use App\Models\Subjectvertical;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectverticalSeeder extends Seeder
{
    public function run(): void
    {
        Subjectvertical::create([
            'id' => 1,
            'subject_vertical' => 'Major Mandatory',
            'subjectvertical_shortname' => 'DSC',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 2,
            'subject_vertical' => 'Major Elective',
            'subjectvertical_shortname' => 'DSE',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 3,
            'subject_vertical' => 'Minor',
            'subjectvertical_shortname' => 'M',
            'subjectbuckettype_id' => 2,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 4,
            'subject_vertical' => 'Open Elective',
            'subjectvertical_shortname' => 'OE',
            'subjectbuckettype_id' => 2,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 5,
            'subject_vertical' => 'Vocational Skill Course (VSC)',
            'subjectvertical_shortname' => 'VSC',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 6,
            'subject_vertical' => 'Skill Enhancement Course (SEC)',
            'subjectvertical_shortname' => 'SEC',
            'subjectbuckettype_id' => 3,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 7,
            'subject_vertical' => 'Ability Enhancement Course',
            'subjectvertical_shortname' => 'AEC',
            'subjectbuckettype_id' => 3,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 8,
            'subject_vertical' => 'Value Education Courses',
            'subjectvertical_shortname' => 'VEC',
            'subjectbuckettype_id' => 3,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 9,
            'subject_vertical' => 'Indian Knowledge System',
            'subjectvertical_shortname' => 'IKS',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 10,
            'subject_vertical' => 'On Job Training',
            'subjectvertical_shortname' => 'OJT',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 11,
            'subject_vertical' => 'Field Project',
            'subjectvertical_shortname' => 'FP',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 12,
            'subject_vertical' => 'Community Engagement and Service',
            'subjectvertical_shortname' => 'CES',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);

        Subjectvertical::create([
            'id' => 13,
            'subject_vertical' => 'Co-curricular Course',
            'subjectvertical_shortname' => 'CC',
            'subjectbuckettype_id' => 3,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
        Subjectvertical::create([
            'id' => 14,
            'subject_vertical' => 'Research Project',
            'subjectvertical_shortname' => 'RP',
            'subjectbuckettype_id' => 1,
            'is_active' => 1,
            'created_at' => '2023-09-06 02:53:21',
            'updated_at' => '2023-09-06 02:53:21'
        ]);
    }
}
