<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hall;
use App\Models\GeoPoint;
use Validator;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HallController extends Controller
{
    //
    public function getHalls(){
        $halls = Hall::all();

        return response()->json([
            "status" => "status",
            "message" => "Point Added Successfully",
            "data" => $halls
        ]);
    }
    public function addPointToHallGeoFence(request $request, $hall_uuid){
        $geo_point['latitude'] = $request->latitude;
        $geo_point['longitude'] = $request->longitude;
        $geo_point['hall_uuid'] = $hall_uuid;
        $geo_point['uuid'] = Str::uuid();
        
        GeoPoint::create($geo_point);

        return response()->json([
            "status" => "status",
            "message" => "Point Added Successfully"
        ], 200);
    }

    public function getHallGeoFencePoints(request $request, $hall_uuid){
        $points = GeoPoint::where('hall_uuid', $hall_uuid)->get();

        return response()->json([
            "status" => "status",
            "message" => "Hall Points Retrieved Successfully",
            "data" => $points
        ], 200); 
    }

    public function clearHallGeoFencePoints($hall_uuid){
        GeoPoint::where('hall_uuid', $hall_uuid)->delete();

        return response()->json([
            "status" => "status",
            "message" => "Hall Points Cleared Successfully"
        ], 200);
    }
}
