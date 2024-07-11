<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subjectbuckettypemaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectBucketTypeMasterSeeder extends Seeder
{
    public function run(): void
    {
        Subjectbuckettypemaster::create([
            'buckettype_name' => 'Major',
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);
        Subjectbuckettypemaster::create([
            'buckettype_name' => 'Minor',
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);
        Subjectbuckettypemaster::create([
            'buckettype_name' => 'College',
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);
        Subjectbuckettypemaster::create([
            'buckettype_name' => 'OE',
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);
    }
}
