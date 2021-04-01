<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall;
use Illuminate\Support\Str;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $halls = [
            [
                "uuid" => Str::uuid(),
                "name" => "Multi-Purpose Hall",
                "longitude" => "",
                "latitude" => ""
            ],
            [
                "uuid" => Str::uuid(),
                "name" => "Edozien Lecture Theatre",
                "longitude" => "",
                "latitude" => ""
            ]
        ];

        foreach($halls as $hall){
            Hall::create($hall);
        }
    }
}
