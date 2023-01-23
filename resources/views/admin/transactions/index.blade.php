@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'course/';

?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Transactions</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Transactions</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post">
      @csrf
      <div class="student-group-form ">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="form-group">
            <input type="text" name="search" value="{{$search??''}}" class="form-control" placeholder="Search  ...">
          </div>
        </div>
        <!-- <div class="col-lg-3 col-md-6">
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
            <button type="submit"  class="btn btn-primary">Search</button>
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
                  <h3 class="page-title">Transactions</h3>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
             
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                <thead class="student-thread">
                  <tr>

                     <th>S.No.</th>
                    <th>User Details</th>
                    <th>TXN No</th>                                                 
                    <th>Remarks</th>                                                 
                    <th>Type</th>                     
                    <th>Amount</th>
                    <th>Date/Time</th>
                  </tr>
                </thead>
                <tbody>
                 <?php if(!empty($transactions)){

                    $i = 1;
                    foreach($transactions as $cat){
                      
                      ?>
                      <tr>
                        <td>{{$i++}}</td>
                       
                          <td>{{$cat->name??''}}<br>{{$cat->mobile_number??''}}<br>{{$cat->email??''}}</td>  
                          <td>{{$cat->txn_no??''}}</td>  
                          <td>{{$cat->reason??''}}</td>  
                          <td>{{ucfirst($cat->type)??''}}</td>  
                          <td>{{$cat->amount??''}}</td>  
                          <td>{{date('d M Y,h:i A',strtotime($cat->created_at))}}</td>  
                          
                      </tr>
                    <?php }}?>
                  </tbody>
                </table>
              {{ $transactions->appends(request()->input())->links('admin.pagination') }}

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  @endsection
