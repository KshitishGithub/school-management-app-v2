<?php

namespace App\Http\Controllers;

use App\Jobs\SendOneSignalNotification;
use App\Models\Notice;
use App\Models\setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function notice()
    {
        $notices = Notice::orderBy("id", "desc")->get();
        return view('setting.notice', compact('notices'));
    }

    public function notice_add()
    {
        return view('setting.notice_add');
    }


    // Store Notice.........
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
        ];

        $notice_file = null; // Initialize $notice_file to null

        // Check if 'notice_file' exists in the request
        if ($request->hasFile('notice_file')) {
            $rules['notice_file'] = 'mimes:png,jpg,jpeg,pdf';
            $notice_file = $request->file('notice_file'); // Set $notice_file when it exists
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            // Validation passed
            if ($notice_file) {
                // Upload Event Image
                $extension = $notice_file->getClientOriginalExtension();
                $imageName = time() . '.' . $extension;
                // Store Image in Storage Folder
                $notice_file->move(public_path('uploads/images/notice'), $imageName);
            }

            $notice = new Notice();
            $notice->title = $request->input('title');

            if (isset($imageName)) {
                $notice->file = $imageName; // Set the 'file' attribute when a file is uploaded
            } else {
                $notice->link = $request->input('notice_link'); // Set the 'link' attribute when 'notice_file' is not uploaded
            }

            $notice->save();

            // ! Send notification using onesignal notification --------------------------------
            $bigPicture = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRr-bvjB8eEi-689FZhB6CEFhx7B5aMmn2RuA&s';
            $settings   = Setting::first();
            $largeIcon  = asset('uploads/images/setting/' . $settings->logo);

            // 🔥 Dispatch Job instead of direct function call
            SendOneSignalNotification::dispatch(
                "📢📢📢 {$request->title}",
                "📌📌📌 New notice added now",
                $bigPicture,
                $largeIcon
            );

            return response()->json([
                'status' => true,
                'notification' => true,
                'message' => "Notice added successfully.",
            ]);
        } else {

            // Validation failed
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }

    // Download notice
    public function download_notice($file)
    {
        $path = public_path('uploads/images/notice/' . $file);

        if (file_exists($path)) {
            return response()->download($path, $file);
        } else {
            // Handle the case where the file doesn't exist
            abort(404);
        }
    }

    // Delete notice.....
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            $destroyNotice = Notice::find($request->id);
            $path = public_path("uploads/images/notice/$destroyNotice->file");

            if (File::exists($path)) {
                File::delete($path);
            }

            if ($destroyNotice->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Notice deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $destroyNotice->errors(),
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Request not supported.",
            ]);
        }
    }
}
