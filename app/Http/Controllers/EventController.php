<?php

namespace App\Http\Controllers;

use App\Models\event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index(){
        $events = event::orderBy("created_at","desc")->paginate(6);
        return view('event.list_event',compact('events'));
    }

    public function add(){
        return view('event.add_event');
    }


    // Store Event.........
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title'=> 'required',
            'eventImage'=> 'required|mimes:png,jpg,jpeg|max:2048',
            'description'=> 'required',
        ]);

        if ($validator->passes()) {
            // Upload Event Image
            $eventImage = $request->file('eventImage');
            $extention = $eventImage->getClientOriginalExtension();
            $imageName = time().'.'.$extention;
            // Store Image in Storage Folder
            $eventImage->move(public_path('uploads/images/event'), $imageName);

            // Store image in public folder
            // return $eventImage->move(public_path('images/event'), $imageName);

            $event = new event();
            $event->title = $request->title;
            $event->banner = $imageName;
            $event->description = $request->description;
            $event->save();

            // session()->flash('success','Section added successfully.');

            // Session::flash('toastr', ['type' => 'success', 'message' => 'Class added successfully.']);

            return response()->json([
                'status' => true,
                'message'=> "Event added successfully.",
            ]);

        }else{

            return response()->json([
                'status' => false,
                'message'=> $validator->errors(),
            ]);

        }
    }

    // Delete Event
    public function destroy(Request $request){
        if (!empty($request->id)){
            $destroyEvent = event::find( $request->id );
            $path = public_path("uploads/images/event/$destroyEvent->banner");
            // if(file_exists($path)){
            //     unlink($path);
            // }

            if ( File::exists( $path ) ) {
                File::delete( $path );
            }

            if($destroyEvent->delete() ){
                // session()->flash('success','Event deleted successfully.');
                return response()->json([
                    'status'=> true,
                    'message'=> 'Event deleted successfully.',
                ]);
            }else{
                return response()->json([
                    'status'=> false,
                    'message'=> $destroyEvent->errors(),
                ]);
            }
        }else{
            return response()->json([
                'status'=> false,
                'message'=> "Request not supported.",
            ]);
        }
    }
}
