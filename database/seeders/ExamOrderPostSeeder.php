<?php

namespace Database\Seeders;

use App\Models\Examorderpost;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExamOrderPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                
        Examorderpost::create(array (
            'id' => 1,
            'post_name' => 'Chairman & Moderator ',
            'status' => 1,
            'deleted_at' => NULL,
            'created_at' => '2021-12-22T10:16:32.000000Z',
            'updated_at' => '2021-12-22T10:16:32.000000Z',
        ));
        
        
        Examorderpost::create(array (
            'id' => 2,
            'post_name' => 'Paper Setter & Moderator',
            'status' => 1,
            'deleted_at' => NULL,
            'created_at' => '2021-12-22T10:16:32.000000Z',
            'updated_at' => '2021-12-22T10:16:32.000000Z',
        ));
        
        
        Examorderpost::create(array (
            'id' => 3,
            'post_name' => 'Paper Setter',
            'status' => 1,
            'deleted_at' => NULL,
            'created_at' => '2021-12-22T10:16:32.000000Z',
            'updated_at' => '2021-12-22T10:16:32.000000Z',
        ));
        
        
        Examorderpost::create(array (
            'id' => 4,
            'post_name' => 'Examiner',
            'status' => 1,
            'deleted_at' => NULL,
            'created_at' => '2021-12-22T10:16:32.000000Z',
            'updated_at' => '2021-12-22T10:16:32.000000Z',
        ));
        
        
        Examorderpost::create(array (
            'id' => 6,
            'post_name' => 'Moderator',
            'status' => 1,
            'deleted_at' => NULL,
            'created_at' => '2021-12-22T10:16:32.000000Z',
            'updated_at' => '2021-12-22T10:16:32.000000Z',
        ));
        

    }
}
