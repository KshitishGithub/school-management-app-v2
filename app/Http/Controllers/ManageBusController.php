<?php

namespace App\Http\Controllers;

use App\Models\ManageBus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManageBusController extends Controller
{
    // Bus list
    public function index()
    {
        $buses = DB::table('manage_buses as mb')->join('routes as r', 'r.id', 'mb.route')->get(['mb.*', 'r.route']);
        return view('bus.list_bus', compact('buses'));
    }



    // Add bus routes
    public function add()
    {
        $routes = Route::all();
        return view('bus.add_bus', compact('routes'));
    }

    // Store bus routes
    public function store(Request $request)
    {
        // Validate the request........
        $validator = Validator::make($request->all(), [
            'bus_name' => 'required',
            'bus_type' => 'required',
            'bus_number' => 'required',
            'total_seat' => 'required',
            'driver_name' => 'required',
            'driver_mobile' => 'required',
            'route' => 'required',
            'bus_photo' => 'required|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($validator->passes()) {
            $photo = $request->file('bus_photo');
            $ext = $photo->getClientOriginalExtension();
            $photoName = time() . '.' . $ext;
            $photo->move(public_path('uploads/images/bus'), $photoName);

            $bus = new ManageBus;
            $bus->bus_name = $request->bus_name;
            $bus->bus_type = $request->bus_type;
            $bus->bus_number = $request->bus_number;
            $bus->total_seat = $request->total_seat;
            $bus->driver_name = $request->driver_name;
            $bus->driver_mobile = $request->driver_mobile;
            $bus->route = $request->route;
            $bus->bus_photo = $photoName;
            $bus->save();

            session()->flash('success', 'Bus added successfully.');
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }

    // Delete a bus
    public function destroy(Request $request)
    {
        if ($request->id !== '') {
            $bus = ManageBus::find($request->id);   
            if ($bus) {
                // Delete the photo if it exists
                $photoPath = public_path('uploads/images/bus/' . $bus->bus_photo);
                if (!empty($bus->bus_photo) && File::exists($photoPath)) {
                    File::delete($photoPath);
                }

                // Delete the bus record
                $bus->delete();

                session()->flash('success', 'Bus deleted successfully.');
                return response()->json(['status' => true]);
            } else {
                return response()->json(['status' => false, 'message' => 'Bus not found.']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Bus ID not provided.']);
        }
    }
}
