<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class TeamController extends Controller
{
    public function AllTeam(){

        $team = Team::latest()->get();
        return view('backend.team.all_team',compact('team'));

    } // End Method

    public function AddTeam(){

        return view('backend.team.add_team');

    } // End Method

    public function StoreTeam(Request $request){

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(550,670)->save('upload/team/'.$name_gen);
        $save_url = 'upload/team/'.$name_gen;

        Team::insert([

            'name' => $request->name,
            'position' => $request->position,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'pinterest' => $request->pinterest,
            'image' => $save_url,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Team Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.team')->with($notification);

    } // End Method

    public function EditTeam($id){

        $team = Team::findOrFail($id);
        return view('backend.team.edit_team',compact('team'));

    } // End Method

    public function UpdateTeam(Request $request){

        $team_id = $request->id;
        $team_img = $request->old_img;

        if($request->file('image')){

            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(550,670)->save('upload/team/'.$name_gen);
            $save_url = 'upload/team/'.$name_gen;

            if (file_exists($team_img)) {
                unlink($team_img);
                }

            Team::findOrFail($team_id)->update([

                'name' => $request->name,
                'position' => $request->position,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'linkedin' => $request->linkedin,
                'pinterest' => $request->pinterest,
                'image' => $save_url,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Team Updated With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.team')->with($notification);

            }else{

                Team::findOrFail($team_id)->update([

                    'name' => $request->name,
                    'position' => $request->position,
                    'facebook' => $request->facebook,
                    'twitter' => $request->twitter,
                    'linkedin' => $request->linkedin,
                    'pinterest' => $request->pinterest,
                    'created_at' => Carbon::now(),

                ]);

                $notification = array(
                    'message' => 'Team Updated Without Image Successfully',
                    'alert-type' => 'success'
                );

                return redirect()->route('all.team')->with($notification);

            } // End else

        } // End Method

        public function DeleteTeam($id){

            $team = Team::findOrFail($id);
            $img = $team->image;
            unlink($img);

            Team::findOrFail($id)->delete();

            $notification = array(
                'message' => 'Team Deleted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        } // End Method





}
