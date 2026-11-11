<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class SettingController extends Controller
{

    public function passwordPage()
    {
        return view('backend.pages.password.create');
    }

    public function UpdatePassword(Request $request)
    {
        $data=$request->all();

        $check_password=User::where(['email'=>Auth::user()->email])->first();

        $current_password=$data['c_password'];
        if (Hash::check($current_password, $check_password->password)) {
            $password = bcrypt($data['new_password']);
            User::where('id',$check_password->id)->update(['password'=>$password]);

            return redirect()->back()->with('success', 'Update password Successfully');

        } else {

            return redirect()->back()->with('error', 'Current password incorrect');

        }
    }

    public function index()
    {
        //$web_setting = Setting::first();
        return view('backend.pages.setting.index');
    }

    public function update(Setting $setting, Request $request)
    {
        $setting->phone = $request->phone;
        $setting->email = $request->email;
        $setting->address = $request->address;
        $setting->facebook = $request->facebook;
        $setting->twitter = $request->twitter;
        $setting->instagram = $request->instagram;
        $setting->about_company = $request->about_company;
        $setting->before_travel = $request->before_travel;
        $setting->privacy_policy = $request->privacy_policy;
        $setting->terms_and_condition = $request->terms_and_condition;
        $setting->about_us = $request->about_us;
        $setting->save();
        return redirect()->back();
    }

    public function saveSetting($settings)
    {
        unset($settings['_token']);
        // Remove unnecessary key/values
        foreach ($settings as $key => $value) {
            $saveSetting = Setting::firstOrNew(['key' => $key]);
            $saveSetting->value = $value;
            $saveSetting->save();
        }
        Cache::forget('sys_settings');
        clear_response_cache();
        return true;
    }

    public function store(Request $request)
    {
        // A malformed multipart body gets parsed into a single field holding the raw
        // "Content-Disposition" parts, which then shows up in the site header.
        $noRawMultipart = function ($attribute, $value, $fail) {
            if (is_string($value) && stripos($value, 'Content-Disposition:') !== false) {
                $fail('The ' . str_replace('_', ' ', $attribute) . ' field contains invalid form data.');
            }
        };

        $shortField = ['nullable', 'string', 'max:255', $noRawMultipart];

        $rules = [
            'phone' => ['nullable', 'string', 'max:50', $noRawMultipart],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => $shortField,
            'facebook' => $shortField,
            'linkedin' => $shortField,
            'instagram' => $shortField,
            'youtube' => $shortField,
            'x' => $shortField,
            'pinterest' => $shortField,
        ];

        foreach (array_keys($request->except(array_keys($rules))) as $key) {
            $rules[$key] = [$noRawMultipart];
        }

        $request->validate($rules);

        $this->saveSetting($request->all());
        Cache::flush();
        return redirect()->back()->with('success', 'Setting Saved Successfully!');
    }







}
