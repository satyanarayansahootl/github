@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'news/';
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">News</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All News</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post">
      @csrf
    <div class="student-group-form">
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search  ...">
          </div>
        </div>
       <!--  <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search by Name ...">
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
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
                  <h3 class="page-title">News</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                  <a href="{{ route($routeName.'.news.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                    <th >#ID</th>
                    <th >Category Name</th>
                    <th >Title</th>
                    <th >Type</th>
                    <th >Image</th>
                    <th >Status</th>
                    <th >Date Created</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($news)){

                    $i = 1;
                    foreach($news as $new){
                      ?>
                      <tr>

                        <td>{{$i++}}</td>
                        <td>{{CustomHelper::getCategoryName($new->category_id)}}</td>
                        
                        <td>{{$new->title ?? ''}}</td>
                        <td>{{CustomHelper::getNewsTypeName($new->type)}}</td>
                      
                          <td> <?php
                          if(!empty($new->image)){
                            $image_name = $new->image;
                            if($storage->exists($path.$image_name)){
                              ?>
                              <div class=" image_box" style="display: inline-block">
                                <a href="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" target="_blank">
                                  <img src="{{ url('public/storage/'.$path.'thumb/'.$image_name) }}" style="width:70px;">
                                </a>
                              </div>
                              <?php
                            }
                          }else{?>

                            <?php  }?></td>
                            <td>
                              <select id='change_new_status{{$new->id}}' class="form-control" onchange='change_new_status({{$new->id}})'>
                                <option value='1'<?php if($new->status == 1) echo 'selected'?>>Active</option>
                                <option value='0'<?php if($new->status == 0) echo 'selected'?>>InActive</option>
                              </select>


                            </td>

                            <td>{{date('d M Y',strtotime($new->created_at))}}</td>


                            <td class="text-end">
                              <div class="actions ">
                                <a href="{{ route($routeName.'.news.edit', $new->id.'?back_url='.$BackUrl) }}" class="btn btn-sm bg-success-light me-2 ">
                                  <i class="feather-edit"></i>
                                </a>
                                <a href="{{ route($routeName.'.news.delete', $new->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                                  <i class="feather-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php }}?>
                      </tbody>
                    </table>
                    {{ $news->appends(request()->input())->links('admin.pagination') }}

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      @endsection

      <script>

        function change_new_status(id){
          var status = $('#change_new_status'+id).val();


          var _token = '{{ csrf_token() }}';

          $.ajax({
            url: "{{ route($routeName.'.news.change_new_status') }}",
            type: "POST",
            data: {id:id, status:status},
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