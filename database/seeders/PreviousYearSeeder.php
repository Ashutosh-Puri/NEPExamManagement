<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Previousyear;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PreviousYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = Carbon::now()->year;

        for ($year = $currentYear - 1; $year >= $currentYear - 10; $year--) {
            Previousyear::create([
                'year_name' => $year,
            ]);
        }
    }
}
