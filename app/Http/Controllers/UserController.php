<?php

namespace App\Http\Controllers;

use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    public function getUser($user_uuid){
        $user = $this->getUserDetails($user_uuid);

        return response()->json([
            "status" => "success",
            "message" => "User Retrieved",
            "data" => $user
        ], 200);
    }

    public function getUserDetails($user_uuid){
        $attendance_controller = new AttendanceController();
    
        $user = User::where('uuid', $user_uuid)->first();
        $user['attendance rate'] = $attendance_controller->getUserAttendanceRate($user_uuid);

        return $user;
    }

    public function registerStudent(request $request){
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            // 'middle_name' => 'required',
            'last_name' => 'required',
            'matric_no' => 'required|unique:users',
            'email' => 'required|email|unique:users',
    		'password' => 'required',
        ]);
        // dd($validation);
        if($validation->fails()){
            return response()->json([
                "status" => "failed",
                "message" => "Invalid or Duplicate Input"
            ], 400);
        }

        $data['uuid'] = Str::uuid();
        $data['first_name'] = $request->first_name;
        $data['middle_name'] = $request->middle_name;
        $data['last_name'] = $request->last_name;
        $data['matric_no'] = $request->matric_no;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        $data['user_type'] = 'student';

        $student = User::create($data);

        return response()->json([
            "status" => "success",
            "message" => "Student Registered Successfully",
            "data" => $student
        ], 200);
    }

    public function registerLecturer(request $request){
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            // 'middle_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
    		'password' => 'required',
        ]);
        
        if($validation->fails()){
            return response()->json([
                "status" => "failed",
                "message" => "Invalid or Duplicate Input"
            ], 400);
        }

        $data['uuid'] = Str::uuid();
        $data['first_name'] = $request->first_name;
        $data['middle_name'] = $request->middle_name;
        $data['last_name'] = $request->last_name;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        $data['user_type'] = 'lecturer';

        $lecturer = User::create($data);

        return response()->json([
            "status" => "success",
            "message" => "Lecturer Registered Successfully",
            "data" => $lecturer
        ], 200);
    }

    public function login(request $request){
        $validation = Validator::make($request->all(), [
            "email" => "required",
            "password" => "required"
        ]);

        if($validation->fails()){
            return response()->json([
                "status" => "failed",
                "message" => "Invalid Input"
            ], 400);
        }
        
        if(
            Auth::attempt([
                'matric_no' => request('email'), 
                'password' => request('password') 
            ])||
            Auth::attempt([
                'email' => request('email'), 
                'password' => request('password')
            ])
        ){
            $user = Auth::user(); 
            $token =  $user->createToken('MyApp')->accessToken; 
            
            return response()-> json([
                'status'=>'success',
                'message'=>'Login Successful',
                'data'=> $user,
                'token'=> $token
            ]);
        }else{
            return response()->json([
                "status" => "failed",
                "message" => "Login Failed"
            ], 401);
        }
    }
}
