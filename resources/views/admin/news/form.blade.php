@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$news_id = (isset($news->id))?$news->id:'';
$title = (isset($news->title))?$news->title:'';
$image = (isset($news->image))?$news->image:'';
$description = (isset($news->description))?$news->description:'';
$type = (isset($news->type))?$news->type:'';
$category_id = (isset($news->category_id))?$news->category_id:'';
$author_name = (isset($news->author_name))?$news->author_name:'';
$status = (isset($news->status))?$news->status:'';

$categories = \App\Models\ExamCategory::where('status',1)->where('is_delete',0)->get();


$news_types = CustomHelper::getNewsType();

$exam_categories = CustomHelper::getExamCategory();

?>


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">{{ $page_heading }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">{{ $page_heading }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card comman-shadow">
                    <div class="card-body">
                     <form method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data" role="form">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{$news_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">News Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>

                                 <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label for="userName">Category<span class="text-danger">*</span></label>
                                                <select name="category_id" class="form-control">
                                                    <option>Select Category</option>
                                                    <?php 
                                                    if(!empty($exam_categories)){
                                                        foreach ($exam_categories as $key) {?>
                                                          <option value="{{$key->id}}" <?php if($category_id == $key->id) echo "selected";?>>{{$key->name}}</option>
                                                       <?php }
                                                    }
                                                    ?>
                                                   
                                                </select>

                                            @include('snippets.errors_first', ['param' => 'type'])
                                        </div>
                                    </div>
                              <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label for="userName">Type<span class="text-danger">*</span></label>
                                                <select name="type" class="form-control">
                                                    <option>Select Type</option>
                                                    <?php 
                                                    if(!empty($news_types)){
                                                        foreach ($news_types as $key => $value) {?>
                                                          <option value="{{$key}}" <?php if($type == $key) echo "selected";?>>{{$value}}</option>
                                                       <?php }
                                                    }
                                                    ?>
                                                   
                                                </select>

                                            @include('snippets.errors_first', ['param' => 'type'])
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label for="userName">Title<span class="text-danger">*</span></label>
                                            <input type="text" name="title" value="{{ old('title', $title) }}" id="title" class="form-control"  maxlength="255" placeholder="Enter  title" />

                                            @include('snippets.errors_first', ['param' => 'title'])
                                        </div>
                                    </div>   
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label for="userName">Author Name<span class="text-danger">*</span></label>
                                            <input type="text" name="author_name" value="{{ old('author_name', $author_name) }}" id="author_name" class="form-control"  maxlength="255" placeholder="Enter Author Name" />

                                            @include('snippets.errors_first', ['param' => 'author_name'])
                                        </div>
                                    </div>



                                    <div class="col-12 col-sm-12">
                                        <div class="form-group local-forms">
                                            <label for="userName">Description<span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="description" id="summernote">{{old('description',$description)}}</textarea>


                                            @include('snippets.errors_first', ['param' => 'description'])
                                        </div>
                                    </div>



                                    <div class="col-12 col-sm-4">
                                        <div class="form-group students-up-files">
                                            <label>Upload  Photo </label>
                                            <div class="uplod">
                                                <label class="file-upload image-upbtn mb-0">
                                                    Choose File <input type="file" name="image">
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 col-sm-4">
                                        <label>Status</label>
                                        <div>
                                           Active: <input type="radio" name="status" value="1" <?php echo ($status == '1')?'checked':''; ?> checked>
                                           &nbsp;
                                           Inactive: <input type="radio" name="status" value="0" <?php echo ( strlen($status) > 0 && $status == '0')?'checked':''; ?> >

                                           @include('snippets.errors_first', ['param' => 'status'])
                                       </div>
                                   </div>

                                   <div class="col-12 mt-3">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace( 'description' );
</script>
@endsection


