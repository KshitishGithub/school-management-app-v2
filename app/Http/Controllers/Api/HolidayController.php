<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(){
        $holidays = holiday::orderBy('id','desc')->get();
        if (!empty($holidays)) {
            return response()->json(
                [
                    'status' => true,
                    'data' => $holidays,
                ],200
            );
        }else{
            return response()->json(
                [
                    'status' => false,
                    'data' => '',
                ],404
            );
        }
    }
}
