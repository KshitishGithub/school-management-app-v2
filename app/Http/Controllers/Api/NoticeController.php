<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    public function index()
    {
        $notice = Notice::orderBy("id", "desc")->select(
            'title',
            'link',
            'file',
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') as created_date")
        )->get();

        if ($notice->isNotEmpty()) {
            return response()->json(
                [
                    'status' => true,
                    'path' => url('uploads/images/notice/'),
                    'data' => $notice,
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'data' => '',
                ],
                404
            );
        }
    }
}

