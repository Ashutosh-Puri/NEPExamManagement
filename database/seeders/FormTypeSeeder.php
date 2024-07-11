<?php

namespace Database\Seeders;

use App\Models\Examfeemaster;
use App\Models\Formtypemaster;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FormTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Formtypemaster::create([
            'id' => 1,
            'form_name' => 'Exam Form',
        ]);

        Formtypemaster::create([
            'id' => 2,
            'form_name' => 'Photocopy Form',
        ]);

        Formtypemaster::create([
            'id' => 3,
            'form_name' => 'Revaluation Form',
        ]);

        Formtypemaster::create([
            'id' => 4,
            'form_name' => 'Backlog Form',
        ]);
    }
}
