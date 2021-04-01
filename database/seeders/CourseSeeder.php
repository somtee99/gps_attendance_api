<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $courses = [
            [
                "uuid" => Str::uuid(),
                "title" => "Digital System Design",
                "course_code" => "CEN501"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Microprocessor System and Interfacing",
                "course_code" => "CEN503"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Computer Security Techniques",
                "course_code" => "CEN509"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Computer Software Engineering",
                "course_code" => "CEN511"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Digital Signal Processing",
                "course_code" => "EEE509"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Valuation of Engineering Systems",
                "course_code" => "MEE505"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Operations Research",
                "course_code" => "MEE523"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Engineering Management",
                "course_code" => "MEE527"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Communication Systems",
                "course_code" => "TCE511"
            ],
            [
                "uuid" => Str::uuid(),
                "title" => "Android Application Development 1",
                "course_code" => "ICT501"
            ]    
        ];

        foreach($courses as $course){
            Course::create($course);
        }
    }
}
