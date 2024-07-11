<?php

namespace Database\Seeders;

use App\Models\Coursecategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coursecategory::create([
            'id' => 1,
            'course_category' => 'Professional',
        ]);

        Coursecategory::create([
            'id' => 2,
            'course_category' => 'Non Professional',
        ]);

        Coursecategory::create([
            'id' => 3,
            'course_category' => 'Other',
        ]);
    }
}
