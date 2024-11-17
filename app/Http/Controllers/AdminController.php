<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function AdminDashboard(){

        return view('admin.index');

    } //End Method

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Admin Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/admin/login')->with($notification);

    } //End Method

    public function AdminLogin(Request $request){

        return view('admin.admin_login');

    } //End Method

    public function AdminProfile(Request $request)
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view',compact('profileData'));

    } //End Method

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo'] = $filename;

        } // end if

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method

    public function AdminChangePassword(Request $request)
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_change_password',compact('profileData'));

    } //End Method

    public function AdminPasswordUpdate(Request $request)
    {
        // validation
        $request->validate([

            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {

            $notification = array(
            'message' => 'Old Password Does Not Match!',
            'alert-type' => 'error'
        );

        return back()->with($notification);

        } // end if

        //Update the new password

        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        $notification = array(
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success'
        );

        return back()->with($notification);

    } //End Method


    //////////// Backend User Role Permission for Admin User Method /////////////

    public function AllAdmin() {

        $alladmin = User::where('role','admin')->get();
        return view('backend.pages.admin.all_admin',compact('alladmin'));

    } //End Method


    public function AddAdmin(){

        $roles = Role::all();
        return view('backend.pages.admin.add_admin',compact('roles'));

    } //End Method


    public function StoreAdmin(Request $request){

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = 'active';
        $user->created_at = Carbon::now();
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'Admin User Created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);

    } //End Method

    public function EditAdmin($id){

        $user = User::find($id);
        $roles = Role::all();

        return view('backend.pages.admin.edit_admin',compact('user','roles'));

    } //End Method


    public function UpdateAdmin(Request $request, $id){

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = 'admin';
        $user->status = 'active';
        $user->save();

        $user->roles()->detach();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'Admin User Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.admin')->with($notification);

    } //End Method


    public function DeleteAdmin($id){

        $user = User::find($id);

        if (!is_null($user)) {
            $user->delete();
        }

        $notification = array(
            'message' => 'Admin User Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } //End Method








}
