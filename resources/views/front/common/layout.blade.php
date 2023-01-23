<?php 

// $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$url = url()->current();

$baseurl = url('/');
// $roleId = Auth::guard('admin')->user()->role_id; 
$storage = Storage::disk('public');

$settings = \App\Models\Settings::first();
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();

$logo = config('custom.NO_IMG');

$storage = Storage::disk('public');
$path = 'settings/';

$image_name = $settings->logo ?? '';
if(!empty($image_name)){
    if($storage->exists($path.$image_name)){
        $logo =  url('public/storage/'.$path.'/'.$image_name);
    }
}
$exam_catgories = CustomHelper::getExamCategory();
$exam_id = Session::get('exam')??'';





$tab_keys = Session::get('tab_keys');
// pr($tab_keys);
$type_value = $tab_keys['type']??'news';
$key_value = $tab_keys['key']??'all';



?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ambitious Baba</title>
    <!-- ---------------favicon------------- -->
    <!-- <link rel="shortcut icon" href="assets/image/favicon.ico" type="image/x-icon"> -->

    <!-- Bootstrap css cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="shortcut icon" href="{{url('/')}}/public/storage/settings/favicon.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <!-- slick slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"
    integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"
    integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- custome css -->
    <link rel="stylesheet" href="{{asset('public/web_assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('public/web_assets/css/responsive.css')}}">
</head>
<style type="text/css">
    .max_line1{
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 1; /* number of lines to show */
      line-clamp: 1; 
      -webkit-box-orient: vertical;
  }
</style>
<body>

    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <!-- <div class="preloader"></div> -->
    <header>
        <nav class="navbar navbar-expand-lg bg-light px-4 py-0 shadow-sm fixed-top">
            <div class="container-fluid navbar-grid">
                <a class="navbar-brand text-white" href="{{url('/')}}">
                    <img src="{{asset('public/web_assets/image/ambitiousbaba 1.png')}}" alt="" class="img-fluid" width="100" height="100"></a>

                    <button class="navbar-toggler bg-green-light shadow-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"> </i></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav  mx-auto mb-2 mb-lg-0 align-items-lg-center">
                        <li class="nav-item mx-2  active">
                            <a class="nav-link text-dark " href="{{url('/')}}">Home </a>

                        </li>
                        <li class="nav-item mx-2 ">
                            <a class="nav-link text-dark " href="">My Courses </a>
                        </li>
                        <li class="nav-item mx-2 ">
                            <a class="nav-link text-dark " href=" ">Result</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link text-dark " href="#">Pricing</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link text-dark " href="#">Support</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link text-dark " href="#">Discuss Forum</a>
                        </li>

                    </ul>
                    <form class="d-flex border align-items mb-3 mb-md-0 me-0 me-md-3 rounded" role="search">
                        <select class="form-select" onchange="update_exam_category(this.value,'reload')" aria-label="Default select example">
                            <?php 
                            if(!empty($exam_catgories)){
                                foreach($exam_catgories as $exam){?>
                                    <option value="{{$exam->id}}" <?php if($exam_id == $exam->id) echo "selected"?>>{{$exam->name}}</option>

                                <?php }
                            }
                            ?>

                        </select>
                    </form>
                    <button type="button" class="btn btn-theme mb-3 mb-md-0" data-bs-toggle="modal"
                    data-bs-target="#loginModal">Login/SignUp</button>
                </div>
            </div>
        </nav>
    </header>
    <!-- Login and signUp screen -->
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"> 
                <button type="button" class="btn-close btn-theme ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="container1">
                        <div class="innerBox">
                            <div class="logInBox   py-2" id="log">
                                <h4>Log In</h4>
                                    <div class="form-floating mb-3 mt-3">
                                        <input type="text" class="form-control" id="mobile"
                                        placeholder="Enter Registered Mobile" name="mobile">
                                        <label for="mobile">Enter Registered Mobile</label>
                                    </div>

                                    <div class="form-floating mt-3 mb-3" id="otp_field" style="display: none;">
                                        <input type="text" class="form-control" id="otp" placeholder="Enter OTP"
                                        name="pswd">
                                        <label for="otp">OTP</label>
                                    </div>
                                    <div class="col-12 ">
                                        <button id="send_otp" class="btn btn-theme w-100">Send OTP</button>
                                    </div>
                                    
                                <div class="text-center">
                                    <p> Don't have an Account?<span type="button" class="t-color"
                                        onclick="regis()">SignUp</span></p>
                                    </div>
                                </div>
                                <div class="registrationBox px-4 py-2" id="sign">
                                    <h4>Sign Up</h4>
                                    <form action="">
                                        <div class="form-floating mb-3 mt-3">
                                            <input type="text" class="form-control" id="email" placeholder="Enter full Name"
                                            name="fname">
                                            <label for="fname">Full Name</label>
                                        </div>
                                        <div class="form-floating mb-3 mt-3">
                                            <input type="text" class="form-control" id="email" placeholder="Enter email"
                                            name="email">
                                            <label for="email">Email</label>
                                        </div>

                                        <div class="form-floating mt-3 mb-3">
                                            <input type="text" class="form-control" id="pwd" placeholder="Enter password"
                                            name="pswd">
                                            <label for="pwd">Password</label>
                                        </div>

                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-theme w-100">Sign Up</button>
                                        </div>
                                    </form>
                                    <div class="text-center my-2">
                                        <p>Already have an Account?<span type="button" class="t-color"
                                            onclick="logIn()">Login</span></p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--*********************** nav End****************************** -->

            @yield('content')


            <footer class="pt-5 bg-dark">
                <div class="container">
                    <div class="row g-3 h-100 justify-content-around align-items-center  mt-4">
                        <div class="col-12 col-md-4">
                            <div class="bg-white rounded">
                                <img src="{{asset('public/web_assets/image/logo.png')}}" alt="" class="img-fluid" width="300">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="address">
                                <h6 class="mb-2 text-white">Important links</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-1">
                                        <a href="{{url('/about-us')}}" class="text-white fs-12">About Us</a>
                                    </li>
                                    <li class="mb-1">
                                        <a href="" class="text-white fs-12">Feedback</a>
                                    </li>
                                    <li class="mb-1">
                                        <a href="" class="text-white fs-12">FAQ's</a>
                                    </li>
                                    <li class="mb-1">
                                        <a href="{{url('/contact-us')}}" class="text-white fs-12">Contact Us</a>
                                    </li>

                                    <li class="mb-1">
                                        <a href="" class="text-white fs-12">Term</a>
                                    </li>
                                    <li class="mb-1">
                                        <a href="" class="text-white fs-12">Privacy&Policy</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="address">
                                <h6 class="mb-2 text-white">Subscribe</h6>
                                <p class="fs-12 text-white mb-3">Subscribe to stay tuned for new web design and latest updates.
                                Let's do it! </p>
                                <form class="d-flex bg-white rounded " role="search">
                                    <input class="form-control border-0 me-2" type="search"
                                    placeholder="Enter your email Address" aria-label="Search">
                                    <button class="btn bg-gradients rounded text-white" type="submit">Subscribe</button>
                                </form>
                            </div>
                        </div>


                        <div
                        class="row flex-column flex-lg-row align-items-center justify-content-lg-between justify-content-center border-top py-2">
                        <div class="col-lg-6 col-12 mt-2 mt-md-0 text-lg-start text-center text-white">© 2022 Ambitious
                            Baba. All Rights
                        Reserved </div>
                        <div class="col-12 col-lg-6 text-lg-end text-center">
                            <div
                            class="d-flex align-items-center justify-content-lg-around justify-content-center mt-2 mt-md-0">
                            <a href=" " class="text-white">Terms &amp; Conditions</a>
                            <a href=" " class="text-white">Privacy Policy</a>
                            <a href=" " class="text-white">Sales and Refunds</a>
                            <a href=" " class="text-white">Legal</a>

                        </div>
                    </div>
                </div>
            </footer>
            <!--*****************DownLoad the app End *******************-->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
        </script>
        <!--      slick slider js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
        integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!--  Custome Main js file -->
        <script src="{{asset('public/web_assets/js/main.js')}}"></script>
        <script src="{{asset('public/web_assets/js/typed.js')}}"></script>

    </body>

    </html>

    <script type="text/javascript">

        $( document ).ready(function() {
            var exam_id = '{{$exam_id}}';
            if(exam_id == ''){
                update_exam_category(1,'');
            }
        });

        function update_exam_category(exam,reload=''){
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route('home.update_exam_category') }}",
                type: "POST",
                data: {exam:exam},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    if(resp.status){
                        if(reload == 'reload'){
                            location.reload(); 
                        }
                    }
                    else{

                    }

                }
            });
        }



        function save_tab_keys(type,key){
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route('save_tab_keys') }}",
                type: "POST",
                data: {type:type,key:key},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    if(resp.status){
                                        // if(reload == 'reload'){
                                        //     location.reload(); 
                                        // }
                    }
                    else{

                    }

                }
            });
        }

    </script>

    <script type="text/javascript">
        $("#send_otp").click(function(){
        $('#otp_field').hide();

         var mobile = $('#mobile').val();
         if(mobile == ''){
            alert('Please Enter Correct Mobile Number');
            return false;
         }

           var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route('send_otp') }}",
                type: "POST",
                data: {mobile:mobile},
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    if(resp.success){
                        alert(resp.message);
                        $('#otp_field').show();
                        if(resp.is_new){
                            $('#send_otp').html('Login');
                        }else{
                            $('#send_otp').html('SignUp');
                        }
                    }
                    else{

                    }

                }
            });

      });
  </script>
