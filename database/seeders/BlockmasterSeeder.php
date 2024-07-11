<?php

namespace Database\Seeders;

use App\Models\Blockmaster;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlockmasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
Blockmaster::create(array (
    'id' => 1,
    'block_name' => 'B-1',
    'block_size' => 40,
    'status' => 0,
    'deleted_at' => NULL,
    'created_at' => NULL,
    'updated_at' => NULL,
  ));
  
  
  Blockmaster::create(array (
    'id' => 2,
    'block_name' => 'B-2',
    'block_size' => 30,
    'status' => 1,
    'deleted_at' => NULL,
    'created_at' => NULL,
    'updated_at' => NULL,
  ));
  
  
    }
}
