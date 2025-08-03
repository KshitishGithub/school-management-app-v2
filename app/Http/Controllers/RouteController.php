<?php

namespace App\Http\Controllers;

use App\Models\BusStop;
use App\Models\ManageBus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    // Index routes
    public function index(){
        $routes = Route::all();
        return view('bus.route',compact('routes'));
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route' => 'required',
        ]);

        if ($validator->passes()) {
            $route = new Route();
            $route->route = $request->route;
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
            $route = Route::find($request->id);
            if ($route) {
                ManageBus::where(['route' => $request->id])->delete();
                BusStop::where(['route_id' => $request->id])->delete();
                $route->delete();
                session()->flash('success','Route deleted successfully.');
                return response()->json(array('status' => true));
            } else {
                return response()->json(array('status' => false, 'message' => 'Route not found.'));
            }
        } else {
            return response()->json(array('status' => false, 'message' => 'Route not found.'));
        }
    }

    
}
