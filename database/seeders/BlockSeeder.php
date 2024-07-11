<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Building;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Block::create(array (
            'id' => 1,
            'building_id' => 3,
            'classname' => 'abc',
            'block' => '40',
            'capacity' => 40,
            'noofblocks' => 1,
            'status' => 0,
            'deleted_at' => NULL,
            'created_at' => NULL,
            'updated_at' => NULL,
        ));
        
        
        Block::create(array (
            'id' => 2,
            'building_id' => 3,
            'classname' => 'xyz',
            'block' => '30',
            'capacity' => 30,
            'noofblocks' => 1,
            'status' => 1,
            'deleted_at' => NULL,
            'created_at' => NULL,
            'updated_at' => NULL,
        ));

    }
}
