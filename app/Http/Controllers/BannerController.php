<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function banner()
    {
        $banners = Banner::orderBy("id","desc")->paginate(6);
        return view('setting.banner', compact('banners'));
    }

    public function banner_add()
    {
        return view('setting.banner_add');
    }

    // Store Event.........
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title'=> 'required',
            'bannerImage'=> 'required|mimes:png,jpg,jpeg|max:2048',
            'description'=> 'required',
        ]);

        if ($validator->passes()) {
            // Upload Event Image
            $bannerImage = $request->file('bannerImage');
            $extention = $bannerImage->getClientOriginalExtension();
            $imageName = time().'.'.$extention;
            // Store Image in Storage Folder
            $bannerImage->move(public_path('uploads/images/banner'), $imageName);

            $banner = new Banner();
            $banner->title = $request->title;
            $banner->banner = $imageName;
            $banner->description = $request->description;
            $banner->save();

            return response()->json([
                'status' => true,
                'message'=> "Banner added successfully.",
            ]);

        }else{

            return response()->json([
                'status' => false,
                'message'=> $validator->errors(),
            ]);

        }
    }

    // Delete banner......
    public function destroy(Request $request){
        if (!empty($request->id)){
            $destroyBanner = Banner::find( $request->id );
            $path = public_path("uploads/images/banner/$destroyBanner->banner");

            if ( File::exists( $path ) ) {
                File::delete( $path );
            }

            if($destroyBanner->delete() ){
                return response()->json([
                    'status'=> true,
                    'message'=> 'Banner deleted successfully.',
                ]);
            }else{
                return response()->json([
                    'status'=> false,
                    'message'=> $destroyBanner->errors(),
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
