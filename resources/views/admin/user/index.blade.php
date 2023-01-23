@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';

?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Users</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Users</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post">
      @csrf
      <div class="student-group-form">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by ID ...">
          </div>
        </div>
        <!-- <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Name ...">
          </div>
        </div> -->
        <!-- <div class="col-lg-4 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Phone ...">
          </div>
        </div> -->
        <div class="col-lg-2">
          <div class="search-student-btn">
            <button type="btn" class="btn btn-primary">Search</button>
          </div>
        </div>
      </div>
    </div>
    </form>
    <div class="row">
      <div class="col-sm-12">
        <div class="card card-table comman-shadow">
          <div class="card-body">

            <div class="page-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="page-title">Users</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.user.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>
                    
                    <th >#ID</th>
                    <th >Name</th>
                    <th >Email</th>
                    <th >Phone</th>
                    <th >Status</th>
                    <th >Date Created</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($users)){

              $i = 1;
              foreach($users as $user){
                ?>
                  <tr>
                 
                  <td>{{$i++}}</td>
                  <td>{{$user->name ?? ''}}</td>
                  <td>{{$user->email ?? ''}}</td>
                  <td>{{$user->mobile_number ?? ''}}</td>
                 
                 
                  <td>
                    <select id='change_users_status{{$user->id}}' class="form-control" onchange='change_users_status({{$user->id}})'>
                      <option value='1'<?php if($user->status == 1) echo 'selected'?>>Active</option>
                      <option value='0'<?php if($user->status == 0) echo 'selected'?>>InActive</option>
                    </select>


                  </td>

                  <td>{{date('d M Y',strtotime($user->created_at))}}</td>


                  <td class="text-end">
                    <div class="actions ">
                      <!-- <a href="{{ route($routeName.'.user.profile',['id'=>$user->id,'back_url'=>$BackUrl]) }}" class="btn btn-sm bg-danger-light">
                        <i class="feather-eye"></i>
                      </a> -->


                      <a href="{{ route($routeName.'.user.edit',['id'=>$user->id,'back_url'=>$BackUrl]) }}" class="btn btn-sm bg-success-light me-2 ">
                        <i class="feather-edit"></i>
                      </a>
                      

                       <a href="{{ route($routeName.'.user.delete',['id'=>$user->id,'back_url'=>$BackUrl]) }}" class="btn btn-sm bg-danger-light">
                        <i class="feather-trash"></i>
                      </a>
                    </div>

                  </td>
                </tr>
              <?php }}?>
              </tbody>
            </table>
          {{ $users->appends(request()->input())->links('admin.pagination') }}

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

@endsection

<script>

  function change_users_status(user_id){
    var status = $('#change_users_status'+user_id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.user.change_users_status') }}",
      type: "POST",
      data: {id:user_id, status:status},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.success){
          alert(resp.message);
        }else{
          alert(resp.message);

        }
      }
    });


  }



</script>