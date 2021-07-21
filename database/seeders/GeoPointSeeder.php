<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hall;
use App\Models\GeoPoint;
use Illuminate\Support\Str;

class GeoPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $halls_geopoints = [
            [
                "hall_name" => "Multi-Purpose Hall",
                "geo_points" => [
                    [
                        "latitude" => "6.685372214709272",
                        "longitude" => "3.1695405425962724"
                    ],
                    [
                        "latitude" => "6.685634806750358",
                        "longitude" => "3.1697642570907547"
                    ],
                    [
                        "latitude" => "6.685331815921206",
                        "longitude" => "3.17006462899943"
                    ],
                    [
                        "latitude" => "6.685089423122649",
                        "longitude" => "3.169873767682459"
                    ],
                ]
            ],
            [
                "hall_name" => "Edozien Lecture Theatre",
                "geo_points" => [
                    [
                        "latitude" => "6.684236983515404",
                        "longitude" => "3.1709338173024193"
                    ],
                    [
                        "latitude" => "6.684190158537007",
                        "longitude" => "3.1711477850575855"
                    ],
                    [
                        "latitude" => "6.683902004725045",
                        "longitude" => "3.171091573189703"
                    ],
                    [
                        "latitude" => "6.683944327326812",
                        "longitude" => "3.1708703522902937"
                    ],
                ]
            ],
            [
                "hall_name" => "Hall D",
                "geo_points" => [
                    [
                        "latitude" => "6.688791122299561",
                        "longitude" => "3.1675816357109645"
                    ],
                    [
                        "latitude" => "6.688926684137598",
                        "longitude" => "3.1678986814763492"
                    ],
                    [
                        "latitude" => "6.688763369477682",
                        "longitude" => "3.167963165360834"
                    ],
                    [
                        "latitude" => "6.688634212093595",
                        "longitude" => "3.167657941640939"
                    ],
                ]
            ],
            [
                "hall_name" => "Adenuga Lecture Hall",
                "geo_points" => [
                    [
                        "latitude" => "6.687048616013722",
                        "longitude" => "3.169472559341042"
                    ],
                    [
                        "latitude" => "6.687056177525209",
                        "longitude" => "3.1697237984035374"
                    ],
                    [
                        "latitude" => "6.68715746304325",
                        "longitude" => "3.169897219282543"
                    ],
                    [
                        "latitude" => "6.687355327476567",
                        "longitude" => "3.1699960958682136"
                    ],
                    [
                        "latitude" => "6.6873010468898935",
                        "longitude" => "3.170209737140663"
                    ],
                    [
                        "latitude" => "6.68709543855239",
                        "longitude" => "3.1701865512661334"
                    ],
                    [
                        "latitude" => "6.687042802804086",
                        "longitude" => "3.1702428598185617"
                    ],
                    [
                        "latitude" => "6.686858577640367",
                        "longitude" => "3.170222986211822"
                    ],
                    [
                        "latitude" => "6.686615769463684",
                        "longitude" => "3.1696848172738084"
                    ],
                    [
                        "latitude" => "6.686715587943374",
                        "longitude" => "3.16970089761271"
                    ],
                    [
                        "latitude" => "6.6867914499743035",
                        "longitude" => "3.1694690727268804"
                    ],
                ]
            ],
            // [
            //     "hall_name" => "",
            //     "geo_points" => [
            //         [
            //             "latitude" => "",
            //             "longitude" => ""
            //         ],
            //         [
            //             "latitude" => "",
            //             "longitude" => ""
            //         ],
            //         [
            //             "latitude" => "",
            //             "longitude" => ""
            //         ],
            //         [
            //             "latitude" => "",
            //             "longitude" => ""
            //         ],
            //     ]
            // ],
        ];

        foreach($halls_geopoints as $hall_geopoints){
            $hall_uuid = Hall::where('name', $hall_geopoints['hall_name'])->first()->uuid;
            foreach($hall_geopoints['geo_points'] as $geo_point){
                $geo_point['hall_uuid'] = $hall_uuid;
                $geo_point['uuid'] = Str::uuid();
                GeoPoint::create($geo_point);
            }
        }
    }
}
