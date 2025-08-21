<?php

namespace App\Http\Controllers;

use App\Models\setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::find(1);

        if ($settings === null) {
            $settings = ''; // No record found
        }
        return view('setting.general_settings', compact('settings'));
    }

    // Store setting .........
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'mimes:png,jpg,jpeg|max:1024',
            'favcion' => 'mimes:png,jpeg,jpg|max:512',
            'school_name' => 'required',
            'village' => 'required',
            'post_office' => 'required',
            'police_station' => 'required',
            'district' => 'required',
            'pin_code' => 'required|size:6',
            'state' => 'required',
            'country' => 'required',
        ]);

        if ($validator->passes()) {

            $setting = Setting::find(1);

            if ($setting === null) {
                $setting = new Setting;
            }

            // Check if both 'logo' and 'favicon' files exist in the request
            $logoName = $setting->logo;
            $faviconName = $setting->favicon;

            $destination = public_path('uploads/images/setting');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            if ($request->hasFile('logo') && $request->hasFile('favicon')) {
                $logo = $request->file('logo');
                $favicon = $request->file('favicon');

                // Handle logo upload
                $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();
                $logo->move($destination, $logoName);

                // Handle favicon upload
                $faviconName = time() . '_favicon.' . $favicon->getClientOriginalExtension();
                $favicon->move($destination, $faviconName);

                // Remove old logo and favicon
                if ($setting->logo) {
                    $oldLogoPath = $destination . '/' . $setting->logo;
                    if (File::exists($oldLogoPath)) {
                        File::delete($oldLogoPath);
                    }
                }
                if ($setting->favicon) {
                    $oldFaviconPath = $destination . '/' . $setting->favicon;
                    if (File::exists($oldFaviconPath)) {
                        File::delete($oldFaviconPath);
                    }
                }
            } elseif ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();
                $logo->move($destination, $logoName);

                if ($setting->logo) {
                    $oldLogoPath = $destination . '/' . $setting->logo;
                    if (File::exists($oldLogoPath)) {
                        File::delete($oldLogoPath);
                    }
                }
            } elseif ($request->hasFile('favicon')) {
                $favicon = $request->file('favicon');
                $faviconName = time() . '_favicon.' . $favicon->getClientOriginalExtension();
                $favicon->move($destination, $faviconName);

                if ($setting->favicon) {
                    $oldFaviconPath = $destination . '/' . $setting->favicon;
                    if (File::exists($oldFaviconPath)) {
                        File::delete($oldFaviconPath);
                    }
                }
            }



            // Update the setting
            $setting->school_name = $request->school_name;
            $setting->logo = $logoName;
            $setting->favicon = $faviconName;
            $setting->medium = $request->medium;
            $setting->registration = $request->registration;
            $setting->village = $request->village;
            $setting->post_office = $request->post_office;
            $setting->police_station = $request->police_station;
            $setting->district = $request->district;
            $setting->pin_code = $request->pin_code;
            $setting->state = $request->state;
            $setting->country = $request->country;
            $setting->contact = $request->contact;
            $setting->email = $request->email;
            // $setting->firebase_token = $request->firebase_token;
            $setting->one_signal_api_key = $request->one_signal_api_key;
            $setting->one_signal_app_id = $request->one_signal_app_id;
            $setting->registration_prefix = $request->registration_prefix;
            $setting->save();

            return response()->json([
                'status' => true,
                'message' => 'Setting updated successfully.',
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }


    // Configure the websites settings
    public function configure()
    {
        return view('setting.configuration');
    }
}
