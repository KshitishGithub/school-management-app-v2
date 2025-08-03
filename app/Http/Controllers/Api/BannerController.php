<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {

        $banners = Banner::orderBy("id", "desc")->get();
        if (!empty($banners)) {
            return response()->json(
                [
                    'status' => true,
                    'path' => url('uploads/images/banner/'),
                    'data' => $banners,
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
