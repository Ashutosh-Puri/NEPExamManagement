<?php

namespace Database\Seeders;

use App\Models\Banknamemaster;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BanknamemasterSeeder extends Seeder
{
    public function run(): void
    {
        Banknamemaster::create([
            'bank_name' => 'Axis Bank',
            'bank_shortform' => 'AB',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'Bank Of Baroda',
            'bank_shortform' => 'BOB',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'Bank Of Maharashtra',
            'bank_shortform' => 'BOM',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'Central Bank Of India',
            'bank_shortform' => 'CBI',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'HDFC Bank',
            'bank_shortform' => 'HDFC',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'ICICI Bank',
            'bank_shortform' => 'ICICI',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'State Bank Of India',
            'bank_shortform' => 'SBI',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'UCO Bank',
            'bank_shortform' => 'UCO',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);

        Banknamemaster::create([
            'bank_name' => 'Union Bank Of India',
            'bank_shortform' => 'UBI',
            'is_active' => 1,
            'created_at' => '2023-09-25 05:10:43',
            'updated_at' => '2023-09-25 05:10:43'
        ]);
    }
}
