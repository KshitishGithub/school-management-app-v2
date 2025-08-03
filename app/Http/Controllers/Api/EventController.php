<?php

namespace App\Http\Controllers\Api;

use App\Models\event;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(){
        $events = event::orderBy("created_at","desc")->select('title', 'banner','description')->get();;

        if (!empty($events)) {
            return response()->json(
                [
                    'status' => true,
                    'path' => url('uploads/images/event/'),
                    'data' => $events,
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
