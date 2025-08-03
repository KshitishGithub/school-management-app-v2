<?php

namespace App\Http\Controllers;

use App\Models\BusStop;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BusStopController extends Controller
{
    // Index routes
    public function index(){
        $routes = Route::all();
        $stops = DB::table('bus_stops as bs')->join('routes as r','bs.route_id','r.id')->get(['bs.*','r.id as route_id','r.route']);
        return view('bus.bus_stops',compact('routes', 'stops'));
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route' => 'required',
        ]);

        if ($validator->passes()) {
            $route = new BusStop();
            $route->route_id = $request->route;
            $route->bus_stops = $request->bus_stops;
            $route->stops_sl = $request->stops_sl;
            $route->arrival_time = $request->arrival_time;
            $route->left_time = $request->left_time;
            $route->save();

            session()->flash('success','Route added successfully.');
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

    // Delete a route
    public function destroy(Request $request)
    {
        if ($request->id !== '') {
            $route = BusStop::find($request->id);
            if ($route) {
                $route->delete();
                session()->flash('success','Stops deleted successfully.');
                return response()->json(array('status' => true));
            } else {
                return response()->json(array('status' => false, 'message' => 'Stops not found.'));
            }
        } else {
            return response()->json(array('status' => false, 'message' => 'Stops not found.'));
        }
    }

    // Get Section after selecting Route
    public function getStops(Request $request)
    {
        if ($request != null) {
            $busStops = BusStop::where('route_id', $request->route_id)
                ->select('id', 'bus_stops')
                ->get();
            if ($busStops->count() == 0) {
                return response()->json([
                    'status' => false,
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'busStops' => $busStops
                ]);
            }
        }
    }
}
