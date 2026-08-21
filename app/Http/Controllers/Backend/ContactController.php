<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
   public function index()
    {
        return view('backend.setting.create');
    }

    public function saveSetting($settings)
    {
        unset($settings['_token']);
        // Remove unnecessary key/values
        foreach ($settings as $key => $value) {
            $saveSetting = Contact::firstOrNew(['key' => $key]);
            $saveSetting->value = $value;
            $saveSetting->save();
        }
        Cache::forget('sys_settings');
        return true;
    }

    public function store(Request $request)
    {
        $this->saveSetting($request->all());
        Cache::flush();
        return redirect()->back()->with('success', 'Setting Saved Successfully!');
    }
}
