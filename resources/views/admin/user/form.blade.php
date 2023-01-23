@extends('admin.layouts.layouts')
@section('content')
<?php
$BackUrl = CustomHelper::BackUrl();
$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


$users_id = (isset($users->id))?$users->id:'';
$name = (isset($users->name))?$users->name:'';

$dob = (isset($users->dob))?$users->dob:'';
$mobile_number = (isset($users->mobile_number))?$users->mobile_number:'';
$email = (isset($users->email))?$users->email:'';




$status = (isset($users->status))?$users->status:'';






?>


<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">{{ $page_Heading }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">{{ $page_Heading }}</li>
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

                        <input type="hidden" name="id" value="{{$users_id}}">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">User Information <span><?php if(request()->has('back_url')){ $back_url= request('back_url');  ?>
                                    <a href="{{ url($back_url)}}" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
                                <?php }?></span></h5>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="form-group local-forms">
                                    <label for="userName">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $name) }}" id="name" class="form-control"  maxlength="255" placeholder="Enter Name" />

                                    @include('snippets.errors_first', ['param' => 'name'])
                                </div>
                            </div>
                           
                         <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                              <label for="userName">Email<span class="text-danger">*</span></label>
                              <input type="text" name="email" value="{{ old('email', $email) }}" id="email" class="form-control"  maxlength="255" placeholder="Enter Email" />

                              @include('snippets.errors_first', ['param' => 'email'])
                          </div>
                      </div>
                     
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <label for="userName">Phone<span class="text-danger">*</span></label>
                            <input type="text" name="mobile_number" value="{{ old('mobile_number', $mobile_number) }}" id="mobile_number" class="form-control" placeholder="Enter Phone"  maxlength="255" />


                            @include('snippets.errors_first', ['param' => 'phone'])
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <label for="userName">DOB<span class="text-danger">*</span></label>
                        <input type="date" name="dob" value="{{ old('dob', $dob) }}" id="dob" class="form-control" placeholder="Enter Address"  maxlength="255" />


                        @include('snippets.errors_first', ['param' => 'dob'])
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

@endsection
<script>
    CKEDITOR.replace( 'description' );
</script>
