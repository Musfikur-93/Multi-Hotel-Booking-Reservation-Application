<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function BlogCategory(){

        $category = BlogCategory::latest()->get();
        return view('backend.category.blog_category',compact('category'));

    } // End Method

    public function StoreBlogCategory(Request $request){

        BlogCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'Blog Category Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method

    public function EditBlogCategory($id){

        $categories = BlogCategory::find($id);
        return response()->json($categories);

    } // End Method

    public function UpdateBlogCategory(Request $request){

        $cat_id = $request->cat_id;

        BlogCategory::find($cat_id )->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'Blog Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method

    public function DeleteBlogCategory($id){

        BlogCategory::find($id)->delete();

        $notification = array(
            'message' => 'Blog Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method


    ///// All Blog Post Method /////

    public function AllBlogPost(){

        $post = BlogPost::latest()->get();
        return view('backend.post.all_post',compact('post'));

    } // End Method

    public function AddBlogPost(){
        $blogcat = BlogCategory::latest()->get();
        return view('backend.post.add_post',compact('blogcat'));

    } // End Method


    public function StoreBlogPost(Request $request){

        $image = $request->file('post_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(1000,600)->save('upload/post/'.$name_gen);
        $save_url = 'upload/post/'.$name_gen;

        BlogPost::insert([

            'blogcat_id' => $request->blogcat_id,
            'user_id' => Auth::user()->id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),
            'short_desc' => $request->short_desc,
            'long_desc' => $request->long_desc,
            'post_image' => $save_url,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'BlogPost Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.blog.post')->with($notification);

    } // End Method


    public function EditBlogPost($id){

        $blogcat = BlogCategory::latest()->get();
        $post = BlogPost::find($id);

        return view('backend.post.edit_post',compact('blogcat','post'));

    } // End Method

    public function UpdateBlogPost(Request $request){

        $post_id = $request->id;
        $post_img = $request->old_img;

        if($request->file('post_image')){

            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(1000,600)->save('upload/post/'.$name_gen);
            $save_url = 'upload/post/'.$name_gen;

            if (file_exists($post_img)) {
                unlink($post_img);
                }

            BlogPost::findOrFail($post_id)->update([

                'blogcat_id' => $request->blogcat_id,
                'user_id' => Auth::user()->id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),
                'short_desc' => $request->short_desc,
                'long_desc' => $request->long_desc,
                'post_image' => $save_url,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'BlogPost Updated With Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.blog.post')->with($notification);

            }else{

                BlogPost::findOrFail($post_id)->update([

                    'blogcat_id' => $request->blogcat_id,
                    'user_id' => Auth::user()->id,
                    'post_title' => $request->post_title,
                    'post_slug' => strtolower(str_replace(' ','-',$request->post_title)),
                    'short_desc' => $request->short_desc,
                    'long_desc' => $request->long_desc,
                    'created_at' => Carbon::now(),

                ]);

                $notification = array(
                    'message' => 'BlogPost Updated Without Image Successfully',
                    'alert-type' => 'success'
                );

                return redirect()->route('all.blog.post')->with($notification);

            } // End else

        } // End Method


        public function DeleteBlogPost($id){

            $post = BlogPost::findOrFail($id);
            $img = $post->post_image;
            unlink($img);

            BlogPost::findOrFail($id)->delete();

            $notification = array(
                'message' => 'BlogPost Deleted Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);

        } // End Method


        ///////////////// Frontend Blog Details code ////////////////

        public function BlogDetails($slug){

            $blog = BlogPost::where('post_slug',$slug)->first();
            $bcategory = BlogCategory::latest()->get();
            $lpost = BlogPost::latest()->limit(3)->get();

            return view('frontend.blog.blog_details',compact('blog','bcategory','lpost'));

        } // End Method

        public function BlogCatList($id){

            $blog = BlogPost::where('blogcat_id',$id)->get();
            $namecat = BlogCategory::where('id',$id)->first();
            $bcategory = BlogCategory::latest()->get();
            $lpost = BlogPost::latest()->limit(3)->get();

            return view('frontend.blog.blog_cat_list',compact('blog','bcategory','lpost','namecat'));

        } // End Method


        public function BlogList(){

            $blog = BlogPost::latest()->paginate(3);
            $bcategory = BlogCategory::latest()->get();
            $lpost = BlogPost::latest()->limit(3)->get();

            return view('frontend.blog.all_blog',compact('blog','bcategory','lpost'));

        } // End Method


}
