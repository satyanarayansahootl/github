<?php 

$settings = \App\Models\Settings::first();
?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from preschool.dreamguystech.com/template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 16 Nov 2022 07:17:52 GMT -->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>{{$settings->app_name??''}} - Login</title>

	<link rel="shortcut icon" href="{{asset('public/storage/settings/appicon.png')}}">

	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&amp;display=swap" rel="stylesheet">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap/css/bootstrap.min.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/feather/feather.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/icons/flags/flags.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/plugins/fontawesome/css/all.min.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/css/style.css')}}">
	<script src="https://code.jquery.com/jquery-3.6.0.slim.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js">
    </script>
</head>
<body>

	<div class="main-wrapper login-body">
		<div class="login-wrapper">
			<div class="container">
				<div class="loginbox">
					<div class="login-left">
						<img class="img-fluid" src="{{asset('public/assets/img/login.png')}}" alt="Logo">
					</div>
					<div class="login-right">
						<div class="login-right-wrap">
							<h1>Welcome to {{$settings->app_name??''}}</h1>
							<!-- <p class="account-subtitle">Need an account? <a href="register.html">Sign Up</a></p> -->
							@include('snippets.errors')
                                 @include('snippets.flash')
							<h2>Sign in</h2>

							 <form action="{{url('admin/login')}}" method="post">
                                   {{ csrf_field() }}
								<div class="form-group">
									<label>Username <span class="login-danger">*</span></label>
									<input class="form-control" name="email" type="text">
									<span class="profile-views"><i class="fas fa-user-circle"></i></span>
								</div>
								<div class="form-group">
									<label>Password <span class="login-danger">*</span></label>
									<input class="form-control pass-input" name="password" type="text">
									<span class="profile-views feather-eye toggle-password"></span>
								</div>

							<!-- 	<div class="form-group">
									<label>OTP <span class="login-danger">*</span></label>
									<input class="form-control pass-input" name="otp" type="text">
									<span class="profile-views feather-eye toggle-password"></span>
								</div> -->
								<div class="forgotpass">
									<div class="remember-me">
										<label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
											<input type="checkbox" name="radio">
											<span class="checkmark"></span>
										</label>
									</div>
									<!-- <a href="forgot-password.html">Forgot Password?</a> -->
								</div>
								<div class="form-group">
									<button class="btn btn-primary btn-block" type="submit">Login</button>
								</div>
							</form>

							<!-- <div class="login-or">
								<span class="or-line"></span>
								<span class="span-or">or</span>
							</div> -->

							<!-- <div class="social-login">
								<a href="#"><i class="fab fa-google-plus-g"></i></a>
								<a href="#"><i class="fab fa-facebook-f"></i></a>
								<a href="#"><i class="fab fa-twitter"></i></a>
								<a href="#"><i class="fab fa-linkedin-in"></i></a>
							</div> -->

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script src="{{asset('public/assets/js/jquery-3.6.0.min.js')}}"></script>

	<script src="{{asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

	<script src="{{asset('public/assets/js/feather.min.js')}}"></script>

	<script src="{{asset('public/assets/js/script.js')}}"></script>
</body>

<!-- Mirrored from preschool.dreamguystech.com/template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 16 Nov 2022 07:17:52 GMT -->
</html>