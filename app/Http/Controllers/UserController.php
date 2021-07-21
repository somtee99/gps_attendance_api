<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use JWTAuth;
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

    public function getProfile(){
        $user_uuid = Auth::user()->uuid;
        $user = $this->getUserDetails($user_uuid);

        return response()->json([
            "status" => "success",
            "message" => "User Retrieved",
            "data" => $user
        ], 200);
    }

    // public function handshake(){
    //     try {
    //         $user = JWTAuth::parseToken()->authenticate();
    //     } catch (Exception $e) {
    //         if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
    //             return response()->json(['status' => 'Token is Invalid']);
    //         }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
    //             return response()->json(['status' => 'Token is Expired']);
    //         }else{
    //             return response()->json(['status' => 'Authorization Token not found']);
    //         }
    //     }
    // }

    public function getUserDetails($user_uuid){
        $attendance_controller = new AttendanceController();
    
        $user = User::where('uuid', $user_uuid)->first();
        $user['attendance_rate'] = $attendance_controller->getUserAttendanceRate($user_uuid);

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

        //check if Device is already used by another account
        if(Device::where('info', request->device_info)->exists()){
            return response()->json([
                "status" => "failed",
                "message" => "Device is already used by another account"
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

        $device['uuid'] = Str::uuid();
        $device['user_uuid'] = $data['uuid'];
        $device['info'] = $request->device_info;

        Device::create($device);

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
            "password" => "required",
        ]);

        if($validation->fails()){
            return response()->json([
                "status" => "failed",
                "message" => "Invalid Input"
            ], 400);
        }

        //check if Device is already used by another account
        $user_uuid = User::where('email', request('email'))->first()->uuid;
        $device_info = Device::where('user_uuid', $user_uuid)->first()->info;
        if($device_info != request('device_info')){
            return response()->json([
                "status" => "failed",
                "message" => "Device is already used by another account"
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

    public function loginAction(request $request){       
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
            Auth::login($user); 

            return redirect('/lectures');
          
        }else{
            return redirect('/login');
        }
    }

}
