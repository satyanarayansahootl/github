@extends('admin.layouts.layouts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$path = 'influencer/thumb/';



$image = CustomHelper::getImageUrl('user',$users->profile_picture);
if(empty($image)){
  $image = url('public/storage/settings/appicon.png');
}


?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col">
          <h3 class="page-title">Profile</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item active">Profile</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="profile-header">
          <div class="row align-items-center">
            <div class="col-auto profile-image">
              <a href="{{$image}}" target="_blank">
                <img class="rounded-circle" alt="User Image" src="{{$image}}">
              </a>
            </div>
            <div class="col ms-md-n2 profile-user-info">
              <h4 class="user-name mb-0">{{$users->name??''}}</h4>
              <h4 class="user-name mb-0">{{$users->email??''}}</h4>
              <h4 class="user-name mb-0">{{$users->mobile_number??''}}</h4>
              <h6 class="text-muted"></h6>

              <!-- <div class="user-Location"><i class="fas fa-map-marker-alt"></i> {{$users->address??''}}</div> -->
              <!-- <div class="about-text">Lorem ipsum dolor sit amet.</div> -->
            </div>
            
          </div>
        </div>
        <div class="profile-menu">
          <ul class="nav nav-tabs nav-tabs-solid">
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">About</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#loan_details">Loan Details</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#documents">Documents</a>
            </li>


            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#bank_details">Bank Details</a>
            </li> 
          </ul>
        </div>
        <div class="tab-content profile-tab-cont">
          <div class="tab-pane fade show active" id="per_details_tab">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between">
                      <span>Personal Details</span>
                    </h5>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
                      <p class="col-sm-9">{{$users->name??''}}</p>
                    </div>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Date of Birth</p>
                      <p class="col-sm-9">{{$users->dob??''}}</p>
                    </div>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email ID</p>
                      <p class="col-sm-9">{{$users->email??''}}</p>
                    </div>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile</p>
                      <p class="col-sm-9">{{$users->mobile_number??''}}</p>
                    </div>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Address</p>
                      <p class="col-sm-9 mb-0">{{$users->address??''}}</p>
                    </div>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Alternate Phone</p>
                      <p class="col-sm-9 mb-0">{{$users->alternate_no??''}}</p>
                    </div>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Education</p>
                      <p class="col-sm-9 mb-0">{{$users->education??''}}</p>
                    </div>
                    <div class="row">
                      <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Income Type</p>
                      <p class="col-sm-9 mb-0">{{$users->income_type??''}}</p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>


          <div id="loan_details" class="tab-pane fade">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title d-flex justify-content-between">
                  <span>Loan Details</span>
                </h5>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
                  <p class="col-sm-9">{{$users->name??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Date of Birth</p>
                  <p class="col-sm-9">{{$users->dob??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email ID</p>
                  <p class="col-sm-9">{{$users->email??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile</p>
                  <p class="col-sm-9">{{$users->mobile_number??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0">Address</p>
                  <p class="col-sm-9 mb-0">{{$users->address??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0">Referal Code</p>
                  <p class="col-sm-9 mb-0">{{$users->referal_code??''}}</p>
                </div>  
              </div>
            </div>
          </div>

          <div id="documents" class="tab-pane fade">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title d-flex justify-content-between">
                  <span>Document Details</span>
                </h5>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">AdharCard Front</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->adhar_front)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->adhar_front)}}" height="100px" width="100px"></a></p>
                </div>


                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">AdharCard Back</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->adhar_back)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->adhar_back)}}" height="100px" width="100px"></a></p>
                </div>



                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">PAN</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->pan)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->pan)}}" height="100px" width="100px"></a></p>
                </div>


                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Selfi</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->selfi)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->selfi)}}" height="100px" width="100px"></a></p>
                </div>


                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">AdharCard Front</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->adhar_front)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->adhar_front)}}" height="100px" width="100px"></a></p>
                </div>


                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Bank Statement</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->bank_statement)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->bank_statement)}}" height="100px" width="100px"></a></p>
                </div>


                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Salary Slip</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->salary_slip)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->salary_slip)}}" height="100px" width="100px"></a></p>
                </div>


                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">ID Card</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->id_card)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->id_card)}}" height="100px" width="100px"></a></p>
                </div>


                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Voter ID Card Front</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->voter_id_card_front)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->voter_id_card_front)}}" height="100px" width="100px"></a></p>
                </div>

                 <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Voter ID Card Back</p>
                  <p class="col-sm-9"><a href="{{url('public/storage/user_documents/'.$user_documents->voter_id_card_back)}}" target="_blank"><img src="{{url('public/storage/user_documents/'.$user_documents->voter_id_card_back)}}" height="100px" width="100px"></a></p>
                </div>








              </div>
            </div>
          </div>

          <div id="bank_details" class="tab-pane fade">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title d-flex justify-content-between">
                  <span>Bank Details</span>
                </h5>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Account Holder Name</p>
                  <p class="col-sm-9">{{$bank_details->account_holder_name??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Account No. </p>
                  <p class="col-sm-9">{{$bank_details->account_no??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">IFSC Code</p>
                  <p class="col-sm-9">{{$bank_details->ifsc_code??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Bank Name</p>
                  <p class="col-sm-9">{{$bank_details->bank_name??''}}</p>
                </div>
                <div class="row">
                  <p class="col-sm-3 text-muted text-sm-end mb-0">Branch</p>
                  <p class="col-sm-9 mb-0">{{$bank_details->branch??''}}</p>
                </div>
              </div>
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

  <script type="text/javascript">
  //////////Subscription
   $(document).ready(function() {
    $('#subscription_modal_form').on('submit', function(event) {
      event.preventDefault();
      var _token = '{{ csrf_token() }}';
      let subsInfo = $(this).serializeArray();
      let subsdata = {};
      subsInfo.forEach((value) => {
        subsdata[value.name] = value.value;
      });
      let url = "{{ route($routeName.'.user.free_subscription') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: subsdata,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          console.log(resp.message);
          if(resp.status){
            location.reload();
            // $('#amount').html(resp.amount);
            
            // $('#wallet_modal').modal('hide');
            // alert(resp.message);
          }else{
           var messagees = resp.message;
           var errormessage = '';
           for ( var i = 0; i < messagees.length; i++ ) {
            var m = messagees[i];
            if(m !=''){
              errormessage = errormessage+'\n' +m
            }
          }
          if(errormessage !=''){
            alert(errormessage);
          }

        }
      }
    })
    });
  });






   $(document).ready(function() {
    $('#wallet_modal_form').on('submit', function(event) {
      event.preventDefault();
      var _token = '{{ csrf_token() }}';
      let walletInfo = $(this).serializeArray();
      let walletdata = {};
      walletInfo.forEach((value) => {
        walletdata[value.name] = value.value;
      });
      let url = "{{ route($routeName.'.user.wallet_update') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: walletdata,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          console.log(resp.message);
          if(resp.status){

            $('#amount').html(resp.amount);
            
            $('#wallet_modal').modal('hide');
            // alert(resp.message);
          }else{
           var messagees = resp.message;
           var errormessage = '';
           for ( var i = 0; i < messagees.length; i++ ) {
            var m = messagees[i];
            if(m !=''){
              errormessage = errormessage+'\n' +m
            }
          }
          if(errormessage !=''){
            alert(errormessage);
          }

        }
      }
    })
    });
  });







  ///////////////////////////////////////////////////////////////////



   $(document).ready(function() {
    $('#update_profile_modal_form').on('submit', function(event) {
      event.preventDefault();
      var _token = '{{ csrf_token() }}';
      let userInfo = $(this).serialize();


      console.log(userInfo);
      let userdata = {};
      // userInfo.forEach((value) => {
      //   userdata[value.name] = value.value;
      // });

      var form = $('#update_profile_modal_form')[0];
      var formData = new FormData(form);
      let url = "{{ route($routeName.'.user.update_profile') }}";
      $.ajax({
        method: "POST",
        url: url,
        dataType:"JSON",
        data: formData,
        processData: false,
        contentType: false,
        headers:{'X-CSRF-TOKEN': _token},
        success: function(resp){
          console.log(resp.message);
          if(resp.status){

            $('#edit_personal_details').modal('hide');
            location.reload();
            
          }else{
           var messagees = resp.message;
           var errormessage = '';
           for ( var i = 0; i < messagees.length; i++ ) {
            var m = messagees[i];
            if(m !=''){
              errormessage = errormessage+'\n' +m
            }
          }
          if(errormessage !=''){
            alert(errormessage);
          }

        }
      }
    })
    });
  });
</script>