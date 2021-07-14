<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Course;
use App\Models\Hall;
use App\Models\Attendance;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    //
    public function getLecture($lecture_uuid){
        $lecture = $this->getLectureDetails($lecture_uuid);

        return response()->json([
            "status" => "success",
            "message" => "Lecture Retrieved Successfully",
            "data" => $lecture
        ], 200);
    }

    public function getLectureDetails($lecture_uuid){
        $user = Auth::user();

        $lecture = Lecture::where('uuid', $lecture_uuid)->first();
        $lecture['attended'] = Attendance::where('lecture_uuid', $lecture_uuid)
                                ->where('user_uuid', $user->uuid)->exists();

        return $lecture;
    }

    public function setLecture(request $request, Carbon $startTime, Carbon $endTime){
        
        if(!$this->checkIfHallIsAvailable($request->hall_uuid, $startTime, $endTime)){
            return response()->json([
                "status" => "failed",
                "message" => "Hall Is Occupied During That Period"
            ], 400);
        }

        $data['uuid'] = Str::uuid();
        $data['course_uuid'] = $request->course_uuid;
        $data['hall_uuid'] = $request->hall_uuid;
        $data['start_time'] = $startTime;    
        $data['end_time'] = $endTime;    

        $lecture = Lecture::create($data);
        return response()->json([
            "status" => "success",
            "message" => "Lecture Set Successfully",
            "data" => $lecture
        ], 200);
    }

    public function createLectureAction(request $request){

        $data['uuid'] = Str::uuid();
        $data['course_uuid'] = $request->course_uuid;
        $data['hall_uuid'] = $request->hall_uuid;
        $data['start_time'] = $request->start_time;    
        $data['end_time'] = $request->end_time;     

        $lecture = Lecture::create($data);
        return redirect('/lectures');
    }

    public function checkIfHallIsAvailable(String $hall_uuid, Carbon $startTime, Carbon $endTime){
        //get lectures for that day
        $lectures = Lecture::where('hall_uuid', $hall_uuid)
                    ->whereDate('start_time', $startTime->toDateString())->get();

        //iterate through the lectures for that day
        foreach($lectures as $lecture){
            $lecture['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $lecture['start_time']);
            $lecture['end_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $lecture['end_time']);
         
            if(($startTime->between($lecture->start_time, $lecture->end_time) && !$startTime->equalTo($lecture->end_time))
            || ($endTime->between($lecture->start_time, $lecture->end_time) && !$endTime->equalTo($lecture->start_time))){
                return false;
            }
        }

        return true;
    }

    //incomplete
    public function setWeeklyLectures(request $request, String $no_of_weeeks){
        $lectures = $request->all();  //array of Lectures (per week)
        $lectures_set = [];
        $lectures_not_set = [];

        for($x = 0; $x < $no_of_weeeks; $x++){
            foreach($lectures as $lecture){
                $lecture['uuid'] = Str::uuid();
                $lecture['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $lecture->start_time)->addWeeks($x);
                $lecture['end_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $lecture->end_time)->addWeeks($x);

                if($this->checkIfHallIsAvailable($lecture->hall_uuid, $lecture->start_time, $lecture->end_time)){
                    $class = Lecture::create($lecture);
                    array_push($lectures_set, $class);
                }else{
                    array_push($lectures_not_set, $class);
                }
                
            }
        }   
        
        return response()->json([
            "status" => "success",
            "message" => "Lectures Set Successfully",
            "data" => [ "lectures set" => $lectures_set,
                        "lectures not set" => $lectures_not_set
                      ]
        ], 200);
    }

    public function editLecture(request $request, $lecture_uuid){
        // $data['course_uuid'] = $request->course_uuid;
        // $data['hall_uuid'] = $request->hall_uuid;
        // $data['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->time); //dateTime format
        // $data['end_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $request->time); //dateTime format
        $lecture = Lecture::where('uuid', $lecture_uuid)->first();
        
        $hall_uuid = $request->hall_uuid ? $request->hall_uuid : $lecture->hall_uuid;
        $start_time = $request->start_time ? $request->start_time : $lecture->start_time;
        $end_time = $request->end_time ? $request->end_time : $lecture->end_time;

        //change to carbon class
        $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $start_time);
        $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $end_time);

        if(!$this->checkIfHallIsAvailable($hall_uuid, $start_time, $end_time)){
            return response()->json([
                "status" => "failed",
                "message" => "Hall Is Occupied During That Period"
            ], 400);
        }

        try {
            Lecture::where('uuid', $lecture_uuid)->update($request->all());
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "failed",
                "message" => "Unknown Variable Detected",
                "error" => $th
            ], 400);
        }
        
        $lecture = Lecture::where('uuid', $lecture_uuid)->first();
        return response()->json([
            "status" => "success",
            "message" => "Lecture Edited Successfully",
            "data" => $lecture
        ], 200);
    }

    public function deleteLecture($lecture_uuid){
        $lecture = Lecture::where('uuid', $lecture_uuid)->first()->delete();

        return response()->json([
            "status" => "success",
            "message" => "Lecture Deleted Successfully"
        ], 200);
    }

    public function getNextLectures(request $request){
        $user = Auth::user();
        $now = Carbon::now();

        if($user->user_type === "student"){
            $courses = $user->courses()->wherePivot('status', 'enrolled')->get();
        }else if($user->user_type === "lecturer"){
            $courses = $user->courses()->wherePivot('status', 'teaching')->get();
        }else{
            $courses = $user->courses()->get();
        }
        
        $lectures = [];
        foreach($courses as $course){
            $course_lectures = $course->lectures()->orderBy('start_time', 'asc')->get();
            foreach($course_lectures as $lecture){
                $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $lecture->start_time);
                if($dateTime->greaterThanOrEqualTo($now)){
                    array_push($lectures, $lecture);
                }
            }
        }

        return response()->json([
            "status" => "success",
            "message" => "User's Next Lectures Retrieved Successfully",
            "data" => $lectures
        ], 200);
    }

    public function getNextLectures2(request $request){
        $user = Auth::user();
        $now = Carbon::now();
        
        $lectures = [];
        $all_lectures = Lecture::whereNotNull('uuid')->orderBy('start_time', 'asc')->get();
        foreach($all_lectures as $lecture){
            $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $lecture->end_time);
            if($dateTime->greaterThanOrEqualTo($now)){
                $lecture['hall'] = Hall::where('uuid', $lecture->hall_uuid)->first();
                $lecture['course'] = Course::where('uuid', $lecture->course_uuid)->first();
                $lecture['has_signed'] = Attendance::where('user_uuid', $user->uuid)->where('lecture_uuid', $lecture->uuid)
                                        ->where('type', 'sign in')->exists();
                array_push($lectures, $lecture);
            }
        }

        return response()->json([
            "status" => "success",
            "message" => "User's Next Lectures Retrieved Successfully",
            "data" => $lectures
        ], 200);
    }

    public function getLecturesByDay(request $request, $date){
        $user = Auth::user();
        $now = Carbon::now();
        $date = Carbon::createFromFormat('Y-m-d', $date);

        if($user->user_type === "student"){
            $courses = $user->courses()->wherePivot('status', 'enrolled')->get();
        }else if($user->user_type === "lecturer"){
            $courses = $user->courses()->wherePivot('status', 'teaching')->get();
        }else{
            $courses = $user->courses()->get();
        }
        
        $lectures = [];
        foreach($courses as $course){
            $course_lectures = $course->lectures()->orderBy('start_time', 'asc')->get();
            foreach($course_lectures as $lecture){
                $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $lecture->end_time);
                if($dateTime->isSameDay($date)){
                    array_push($lectures, $this->getLectureDetails($lecture_uuid));
                }
            }
        }

        return response()->json([
            "status" => "success",
            "message" => "User's Lectures By Day Retrieved Successfully",
            "data" => $lectures
        ], 200);
    }

    public function getPreviousLectures(request $request){
        $user = Auth::user();
        $now = Carbon::now();

        if($user->user_type === "student"){
            $courses = $user->courses()->wherePivot('status', 'enrolled')->get();
        }else if($user->user_type === "lecturer"){
            $courses = $user->courses()->wherePivot('status', 'teaching')->get();
        }else{
            $courses = $user->courses()->get();
        }
        
        $lectures = [];
        foreach($courses as $course){
            $course_lectures = $course->lectures()->orderBy('start_time', 'desc')->get();
            foreach($course_lectures as $lecture){
                $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $lecture->end_time);
                if($dateTime->lessThan($now)){
                    array_push($lectures, $lecture);
                }
            }
        }

        return response()->json([
            "status" => "success",
            "message" => "User's Previous Lectures Retrieved Successfully",
            "data" => $lectures
        ], 200);
    }

    public function getUserPreviousLectures(request $request){
        $user = Auth::user();
        $now = Carbon::now();

        if($user->user_type === "student"){
            $courses = $user->courses()->wherePivot('status', 'enrolled')->get();
        }else if($user->user_type === "lecturer"){
            $courses = $user->courses()->wherePivot('status', 'teaching')->get();
        }else{
            $courses = $user->courses()->get();
        }
        
        $lectures = [];
        foreach($courses as $course){
            $course_lectures = $course->lectures()->orderBy('start_time', 'desc')->get();
            foreach($course_lectures as $lecture){
                $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $lecture->end_time);
                if($dateTime->lessThan($now)){
                    array_push($lectures, $lecture);
                }
            }
        }

        return $lectures;
    }

    public function getLectures(request $request){
        $user = Auth::user();
        
        $lectures = $this->getUserLectures($user->uuid);

        return response()->json([
            "status" => "success",
            "message" => "User's Lectures Retrieved Successfully",
            "data" => $lectures
        ], 200);
    }

    public function getUserLectures($user_uuid, $course_uuid = null){
        $course_controller = new CourseController();

        if($course_uuid){
            $courses =  Course::where('uuid', $course_uuid)->get();
        }else{
            $courses = $course_controller->getUserCourses($user_uuid);
        }
        
        $lectures = [];
        foreach($courses as $course){
            $course_lectures = $course->lectures()->orderBy('start_time', 'asc')->get();
            foreach($course_lectures as $lecture){
                array_push($lectures, $lecture);
            }
        }

        return $lectures;
    }

}
