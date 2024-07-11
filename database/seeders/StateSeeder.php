<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::create([
            'id' => 1,
            'state_code' => 35,
            'state_name' => 'Andaman And Nicobar Islands',
            'census_code' => 35,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 2,
            'state_code' => 28,
            'state_name' => 'Andhra Pradesh',
            'census_code' => 28,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 3,
            'state_code' => 12,
            'state_name' => 'Arunachal Pradesh',
            'census_code' => 12,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 4,
            'state_code' => 18,
            'state_name' => 'Assam',
            'census_code' => 18,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 5,
            'state_code' => 10,
            'state_name' => 'Bihar',
            'census_code' => 10,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 6,
            'state_code' => 4,
            'state_name' => 'Chandigarh',
            'census_code' => 4,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 7,
            'state_code' => 22,
            'state_name' => 'Chhattisgarh',
            'census_code' => 22,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 8,
            'state_code' => 7,
            'state_name' => 'Delhi',
            'census_code' => 7,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 9,
            'state_code' => 30,
            'state_name' => 'Goa',
            'census_code' => 30,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 10,
            'state_code' => 24,
            'state_name' => 'Gujarat',
            'census_code' => 24,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 11,
            'state_code' => 6,
            'state_name' => 'Haryana',
            'census_code' => 6,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 12,
            'state_code' => 2,
            'state_name' => 'Himachal Pradesh',
            'census_code' => 2,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 13,
            'state_code' => 1,
            'state_name' => 'Jammu And Kashmir',
            'census_code' => 1,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 14,
            'state_code' => 20,
            'state_name' => 'Jharkhand',
            'census_code' => 20,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 15,
            'state_code' => 29,
            'state_name' => 'Karnataka',
            'census_code' => 29,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 16,
            'state_code' => 32,
            'state_name' => 'Kerala',
            'census_code' => 32,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 17,
            'state_code' => 37,
            'state_name' => 'Ladakh',
            'census_code' => 0,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 18,
            'state_code' => 31,
            'state_name' => 'Lakshadweep',
            'census_code' => 31,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 19,
            'state_code' => 23,
            'state_name' => 'Madhya Pradesh',
            'census_code' => 23,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 20,
            'state_code' => 27,
            'state_name' => 'Maharashtra',
            'census_code' => 27,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 21,
            'state_code' => 14,
            'state_name' => 'Manipur',
            'census_code' => 14,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 22,
            'state_code' => 17,
            'state_name' => 'Meghalaya',
            'census_code' => 17,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 23,
            'state_code' => 15,
            'state_name' => 'Mizoram',
            'census_code' => 15,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 24,
            'state_code' => 13,
            'state_name' => 'Nagaland',
            'census_code' => 13,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 25,
            'state_code' => 21,
            'state_name' => 'Odisha',
            'census_code' => 21,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 26,
            'state_code' => 34,
            'state_name' => 'Puducherry',
            'census_code' => 34,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 27,
            'state_code' => 3,
            'state_name' => 'Punjab',
            'census_code' => 3,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 28,
            'state_code' => 8,
            'state_name' => 'Rajasthan',
            'census_code' => 8,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 29,
            'state_code' => 11,
            'state_name' => 'Sikkim',
            'census_code' => 11,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 30,
            'state_code' => 33,
            'state_name' => 'Tamil Nadu',
            'census_code' => 33,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 31,
            'state_code' => 36,
            'state_name' => 'Telangana',
            'census_code' => 0,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 32,
            'state_code' => 38,
            'state_name' => 'The Dadra And Nagar Haveli And Daman And Diu',
            'census_code' => 0,
            'state_or_UT' => 'U',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 33,
            'state_code' => 16,
            'state_name' => 'Tripura',
            'census_code' => 16,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 34,
            'state_code' => 5,
            'state_name' => 'Uttarakhand',
            'census_code' => 5,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 35,
            'state_code' => 9,
            'state_name' => 'Uttar Pradesh',
            'census_code' => 9,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 36,
            'state_code' => 19,
            'state_name' => 'West Bengal',
            'census_code' => 19,
            'state_or_UT' => 'S',
            'country_id' => 1,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);

        State::create([
            'id' => 37,
            'state_code' => 19,
            'state_name' => 'Other',
            'census_code' => 19,
            'state_or_UT' => 'S',
            'country_id' => 2,
            'created_at' => '2023-09-05 21:23:00',
            'updated_at' => '2023-09-05 21:23:00'
        ]);
    }
}
