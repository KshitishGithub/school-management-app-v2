<?php

namespace App\Http\Controllers;

use App\Models\holiday;
use App\Models\setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('start_date')->get();
        return view('holiday.list_holiday', compact('holidays'));
    }

    public function add()
    {
        return view('holiday.add_holiday');
    }


    // Store Event.........
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'holiday' => 'required',
            'day' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validator->passes()) {

            $event = new holiday();
            $event->holiday = $request->holiday;
            $event->day = $request->day;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->save();
            
            // ! Send notification using onesignal notification --------------------------------
            $bigPicture = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQANBw7hZLyuB26YccypLwm2cDCFiFBh-k4qA&s';
            $settings = Setting::all()->first();
            $largeIcon = url('storage/images/setting/' . $settings->logo);
            $notification = OneSignalPushNotification("😍😍😍 $request->holiday", "📅 Next $request->start_date to 📅 $request->end_date is holiday", $bigPicture, $largeIcon);

            if (json_decode($notification)->status == true) {
                return response()->json([
                    'status' => true,
                    'message' => "Holiday added successfully.",
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => "Holiday added successfully.",
                ]);
            }
        } else {

            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }

    // Delete holiday......
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            $destroyBanner = holiday::find($request->id);

            if ($destroyBanner->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Holiday deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $destroyBanner->errors(),
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
