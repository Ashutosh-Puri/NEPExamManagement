<?php

namespace Database\Seeders;

use App\Models\Sanstha;
use App\Models\University;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SansthaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = Sanstha::create(
            [
                'sanstha_name' => 'Shikshan Prasarak Sanstha',
                'sanstha_address' => ' At post- Ghulewadi, Nashik Pune Highway, Sangamner Tal-Sangamner Dist-Ahmednagar, Dist:Ahmednagar Tal:Sangamner',
                'sanstha_chairman_name' => 'Dr.Sanjay Malpani',
                'sanstha_website_url' => 'www.sangamnercollege.edu.in',
                'sanstha_contact_no' => '02425225893',
                'status' => '1',
            ]
        );
        University::create(
            [
                'id' => 1,
                'university_name' => 'Savitribai Phule Pune University, Pune',
                'university_address' => '	Ganeshkhind, Aundh, Pune, Maharashtra, India',
                'university_website_url' => 'http://www.unipune.ac.in/',
                'university_email' => 'bcud[at]unipune[dot]ac[dot]in',
                'university_contact_no' => '25698007',
                'university_logo_path' => '',
                'status' => '1',
            ]
        );


    }
}
