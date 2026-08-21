<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('backend.index');
    }

    public function Profile()
    {
        return view('backend.profile.create');
    }
    public function EditProfile($id)
    {
//        $user_id =auth()->user()->id;
        $profile = User::Where('id',$id)->first();
        return view('backend.profile.edit',compact('profile'));
    }

    public function UpdateProfile(Request $request ,User $profile)

    {

        $profile-> name = $request->name;
        $profile-> email = $request->email;
        $profile->save();

        return redirect()->route('admin.profile.page')->with('success','Profile is updated');

    }


    public function UpdatePasswordPage()
    {
        return view('backend.profile.change-password');
    }

   public function UpdatePassword(Request $request)
    {

        $data=$request->all();

        $check_password=User::where(['email'=>Auth::user()->email])->first();

        $current_password=$data['current_pwd'];
        if (Hash::check($current_password, $check_password->password)) {
            $password = bcrypt($data['new_pwd']);
            User::where('id',$check_password->id)->update(['password'=>$password]);

            return redirect()->route('admin.profile.page')->with('success', 'Update password Successfully');

        } else {

            return redirect()->back()->with('success', 'Current password incorrect');

        }
    }



}
