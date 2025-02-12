<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use App\Models\SiteSetting;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    public function SmtpSetting(){

        $smtp = SmtpSetting::find(1);
        return view('backend.setting.smtp_update',compact('smtp'));

    } // End Method

    public function SmtpUpdate(Request $request){

        $smtp_id = $request->id;

        SmtpSetting::find($smtp_id)->update([
            'mailer' => $request->mailer,
            'host' => $request->host,
            'port' => $request->port,
            'username' => $request->username,
            'password' => $request->password,
            'encryption' => $request->encryption,
            'from_address' => $request->from_address,
        ]);

        $notification = array(
            'message' => 'SMTP Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method


    public function SiteSetting(){

        $site = SiteSetting::find(1);
        return view('backend.site.site_update',compact('site'));

    } // End Method


    public function SiteUpdate(Request $request){

        $site_id = $request->id;
        $site_img = $request->old_img;

        if($request->file('logo')){

            $image = $request->file('logo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(110,44)->save('upload/site/'.$name_gen);
            $save_url = 'upload/site/'.$name_gen;

            if (file_exists($site_img)) {
                unlink($site_img);
                }

            SiteSetting::findOrFail($site_id)->update([

                'phone' => $request->phone,
                'address' => $request->address,
                'footer_desc' => $request->footer_desc,
                'email' => $request->email,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'copyright' => $request->copyright,
                'logo' => $save_url,

            ]);

            $notification = array(
                'message' => 'SiteSetting Updated With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

            }else{

                SiteSetting::findOrFail($site_id)->update([

                    'phone' => $request->phone,
                    'address' => $request->address,
                    'footer_desc' => $request->footer_desc,
                    'email' => $request->email,
                    'facebook' => $request->facebook,
                    'twitter' => $request->twitter,
                    'copyright' => $request->copyright,
                    'logo' => $save_url,

                ]);

                $notification = array(
                    'message' => 'SiteSetting Updated Without Image Successfully',
                    'alert-type' => 'success'
                );

                return redirect()->back()->with($notification);

            } // End else

        } // End Method




}
