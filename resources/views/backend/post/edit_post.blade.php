@extends('admin.admin_dashboard')
@section('main')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
	<!--breadcrumb-->
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Edit Blog Post</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Add Blog Post</li>
				</ol>
			</nav>
		</div>

	</div>
	<!--end breadcrumb-->
	<div class="container">
		<div class="main-body">
			<div class="row">

		<div class="col-lg-10">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Add Post</h5>
                    <form action="{{ route('update.blog.post') }}" method="post" class="row g-3" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" value="{{ $post->id }}">
                        <input type="hidden" name="old_img" value="{{ $post->post_image }}">

                        <div class="col-md-6">
                            <label for="input9" class="form-label">Blog Category</label>
                            <select name="blogcat_id" id="input9" class="form-select">
                                <option selected="">Select Category...</option>
                                @foreach ($blogcat as $cat)
                                    <option value="{{ $cat->id }}" {{ $cat->id == $post->blogcat_id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="input1" class="form-label">Post Title</label>
                            <input type="text" name="post_title" class="form-control" id="input1" value="{{ $post->post_title }}">
                        </div>

                        <div class="col-md-12">
                            <label for="input11" class="form-label">Short Description</label>
                            <textarea class="form-control" name="short_desc" id="input11" placeholder="Address ..." rows="3">{{ $post->short_desc }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="input11" class="form-label">Long Description</label>
                            <textarea name="long_desc" class="form-control" id="myeditorinstance">{!! $post->long_desc !!}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="input1" class="form-label">Post Image</label>
                            <input class="form-control" name="post_image" type="file" id="image">
                        </div>

                        <div class="col-md-6">
                            <label for="input1" class="form-label"></label>
                            <img id="showImage" src="{{ asset($post->post_image) }}" alt="Post" class="rounded-circle p-1 bg-primary" width="80">
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

		</div>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
	    $('#image').change(function(e){
	        var reader = new FileReader();
	        reader.onload = function(e){
	            $('#showImage').attr('src',e.target.result);
	        }
	        reader.readAsDataURL(e.target.files['0']);
	    });
	});
</script>



@endsection
