<?php

use App\Fee;
use Illuminate\Database\Seeder;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fees = [
            12 => 5.9,
            24 => 11.5,
        ];

        foreach ($fees as $weeks => $rate) {
            Fee::create(compact('weeks', 'rate'));
        }
    }
}
