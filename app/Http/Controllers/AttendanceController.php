<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Lecture;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    //
    public function signIn(request $request, $lecture_uuid){
        $user = Auth::user();

        $exists = Attendance::where('user_uuid', $user->uuid)->where('lecture_uuid', $lecture_uuid)
                    ->where('type', 'sign in')->exists();

        if($exists){
            return response()->json([
                "status" => "failed",
                "message" => "User already signed in to this lecture"
            ], 400);  
        }

        if(!$this->checkIfLectureInProgress($lecture_uuid)){
            return response()->json([
                "status" => "failed",
                "message" => "Lecture has not begun or has ended"
            ], 400);
        }

        //verify location here and return location coordinates

        $data['uuid'] = Str::uuid();
        $data['lecture_uuid'] = $lecture_uuid;
        $data['type'] = 'sign in';
        // $data['longitude'] = $request->longitude;
        // $data['latitude'] = $request->latitude;
        // $data['elevation'] = $request->elevation;

        $user->attendances()->create($data);
        return response()->json([
            "status" => "success",
            "message" => "Sign In Successful"
        ], 200);
    }

    public function signOut(request $request, $lecture_uuid){
        $user = Auth::user();

        $exists = Attendance::where('user_uuid', $user->uuid)->where('lecture_uuid', $lecture_uuid)
                    ->where('type', 'sign out')->exists();

        if($exists){
            return response()->json([
                "status" => "failed",
                "message" => "User already signed out from this lecture"
            ], 400);  
        }

        //verify location here and return location coordinates

        $data['uuid'] = Str::uuid();
        $data['lecture_uuid'] = $lecture_uuid;
        $data['type'] = 'sign out';
        // $data['longitude'] = $request->longitude;
        // $data['latitude'] = $request->latitude;
        // $data['elevation'] = $request->elevation;

        $user->attendances()->create($data);
        return response()->json([
            "status" => "success",
            "message" => "Sign Out Successful"
        ], 200);
    }

    public function checkIfLectureInProgress($lecture_uuid){
        $lecture = Lecture::where('uuid', $lecture_uuid)->first();

        // dd([
        //     "now" => Carbon::now()->toDateTimeString(),
        //     "start" => $lecture->start_time,
        //     "end" => $lecture->end_time
        // ]);

        $eligible = Carbon::now()->between($lecture->start_time, $lecture->end_time);

        if($eligible){
            return true;
        }
        return false;
    }

    public function getAttendees($lecture_uuid){
        $attendees_uuids = Attendance::where('lecture_uuid', $lecture_uuid)
                            ->where('type', 'sign in')->pluck('user_uuid');
        $attendees = [];

        foreach($attendees_uuids as $attendee_uuid){
            $attendee = User::where('uuid', $attendee_uuid)->first();
            array_push($attendees, $attendee);
        }

        return response()->json([
            "status" => "success",
            "message" => "Lecture's Attendees Retrieved",
            "data" => $attendees
        ], 200);
    }

    public function getAbsentees($lecture_uuid){
        $users_uuids = Lecture::where('uuid', $lecture_uuid)->first()
                        ->course()->first()->users()->pluck('uuid')->toArray();
        $attendees_uuids = Attendance::where('lecture_uuid', $lecture_uuid)
                            ->where('type', 'sign in')->pluck('user_uuid')->toArray();

        $absentees_uuids = array_diff($users_uuids, $attendees_uuids);
        $absentees = [];

        foreach($absentees_uuids as $absentees_uuid){
            $absentee = User::where('uuid', $absentees_uuid)->first();
            array_push($absentees, $absentee);
        }

        return response()->json([
            "status" => "success",
            "message" => "Lecture's Absentees Retrieved",
            "data" => $absentees
        ], 200);
    }

    public function getUserAttendanceRate($user_uuid, $course_uuid = null){
        $lecture_controller = new LectureController;

        $lectures = $lecture_controller->getUserLectures($user_uuid, $course_uuid);
        $no_of_lectures = 0;
        $no_of_attended = 0;
        
        foreach($lectures as $lecture){
            if(Attendance::where('user_uuid', $user_uuid)->where('lecture_uuid', $lecture->uuid)
                ->where('type', 'sign in')->exists()){
                    $no_of_attended++;
                }
            $no_of_lectures++;
        }

        try {
            $attendance_rate = ($no_of_attended / $no_of_lectures) * 100 ;
        } catch (\Throwable $th) {
            return null;
        }

        return $attendance_rate;
    }
}
