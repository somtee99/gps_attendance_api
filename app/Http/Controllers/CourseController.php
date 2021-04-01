<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    //
    public function registerCourse($course_uuid){
        $student = Auth::user();

        if($student->user_type != "student"){
            return response()->json([
                "status" => "failed",
                "message" => "User is not a Student"
            ], 403);
        }

        $course = Course::where('uuid', $course_uuid)
                    ->first()
                    ->users()
                    ->attach(
                        $student->id, 
                        ["status"=> "enrolled"]
                    );
        return response()->json([
            "status" => "success",
            "message" => "Course Registered Successfully"
        ], 200);
    }

    public function removeCourse($course_uuid){
        $user = Auth::user();

        $course = Course::where('uuid', $course_uuid)->first()->users()->detach($user->id);
        return response()->json([
            "status" => "success",
            "message" => "Course Removed Successfully"
        ], 200);
    }

    public function assignCourse($course_uuid){ //to assign course to a lecturer
        $lecturer = Auth::user();

        if($lecturer->user_type != "lecturer"){
            return response()->json([
                "status" => "failed",
                "message" => "User is not a Lecturer"
            ], 403);
        }

        $course = Course::where('uuid', $course_uuid)
                    ->first()
                    ->users()
                    ->attach(
                        $lecturer->id, 
                        ["status"=> "teaching"]
                    );
        return response()->json([
            "status" => "success",
            "message" => "Course Assigned to Lecturer Successfully"
        ], 200);
    }

    public function getCourses($level = null){
        $courses = Course::all();

        if(!$level){
            return response()->json([
                "status" => "success",
                "message" => "All Courses Retrieved Successfully",
                "data" => $courses
            ], 200);
        }else{
            $level_year = substr($level, 0, 1);
            // dd($level_year);
        }

        $filtered_courses = [];
        foreach($courses as $course){
            $course_level = substr($course->course_code, 3, 1);
            
            if($course_level == $level_year){
                array_push($filtered_courses, $course);
            }
        } 
        return response()->json([
            "status" => "success",
            "message" => "Courses For ".$level." Level Retrieved Successfully",
            "data" => $filtered_courses
        ], 200);
    }

    public function createCourse(request $request){
        $validation = Validator::make($request->all(), [
            "title" => ['required', 'unique:courses'],
            "course_code" => ['required', 'unique:courses'],
        ]);

        if($validation->fails()){
            return response()->json([
                "status" => "failed",
                "message" => "Invalid or Duplicate Input"
            ], 400);
        }

        $data['uuid'] = Str::uuid();
        $data['title'] = $request->title;
        $data['course_code'] = strtoupper($request->course_code); 

        $course = Course::create($data);
        return response()->json([
            "status" => "success",
            "message" => "Course Created Successfully",
            "data" => $course
        ], 200);
    }

    public function getMyCourses(){
        $user = Auth::user();
        $courses = $this->getUserCourses($user->uuid);

        return response()->json([
            "status" => "sucesss",
            "message" => "My Courses Retrieved",
            "data" => $courses
        ], 200);
    }

    public function getUserCourses($user_uuid){
        $user = User::where('uuid', $user_uuid)->first();

        if($user->user_type === "student"){
            $courses = $user->courses()->wherePivot('status', 'enrolled')->get();
        }else if($user->user_type === "lecturer"){
            $courses = $user->courses()->wherePivot('status', 'teaching')->get();
        }else{
            $courses = $user->courses()->get();
        }

        return $courses;
    }
}
