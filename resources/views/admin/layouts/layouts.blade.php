<?php 

$ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

$url = url()->current();

$baseurl = url('/');
$roleId = Auth::guard('admin')->user()->role_id; 
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


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>{{$settings->app_name??''}} - Dashboard</title>

	<link rel="shortcut icon" href="{{url('/public/storage/settings/appicon.png')}}">

	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&amp;display=swap" rel="stylesheet">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/bootstrap/css/bootstrap.min.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/feather/feather.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/icons/flags/flags.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/plugins/fontawesome/css/all.min.css')}}">

	<link rel="stylesheet" href="{{asset('public/assets/css/style.css')}}">
	<script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js" ></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>



</head>
<style type="text/css">
	.pager {
		padding-left: 0;
		margin: 20px 0;
		text-align: center;
		list-style: none;
	}
	.pager li {
		display: inline;
	}.pager li>a, .pager li>span {
		display: inline-block;
		padding: 5px 14px;
		background-color: #fff;
		border: 1px solid #ddd;
		border-radius: 15px;
	}
	.multipleselectdropdown {
		border: 1px solid #ddd;
		box-shadow: none;
		color: #333;
		font-size: 15px;
		height: 93px;
		width: 100%;
	}




.maxtwoline {
   overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 2; /* number of lines to show */
           line-clamp: 2; 
   -webkit-box-orient: vertical;
}
	</style>
	<body>

		<div class="main-wrapper">

			<div class="header">

				<div class="header-left">
					<a href="{{url('/admin')}}" class="logo">
						<img src="{{$logo}}" alt="Logo">
					</a>
					<a href="{{url('/admin')}}" class="logo logo-small">
						<img src="{{url('/public/storage/settings/appicon.png')}}" alt="Logo" width="30" height="30">
					</a>
				</div>

				<div class="menu-toggle">
					<a href="javascript:void(0);" id="toggle_btn">
						<i class="fas fa-bars"></i>
					</a>
				</div>

				<div class="top-nav-search">
					<form>
						<input type="text" class="form-control" placeholder="Search here">
						<button class="btn" type="submit"><i class="fas fa-search"></i></button>
					</form>
				</div>


				<a class="mobile_btn" id="mobile_btn">
					<i class="fas fa-bars"></i>
				</a>


				<ul class="nav user-menu">
					

					

					<li class="nav-item zoom-screen me-2">
						<a href="#" class="nav-link header-nav-list win-maximize">
							<img src="{{asset('public/assets/img/icons/header-icon-04.svg')}}" alt="">
						</a>
					</li>

					<li class="nav-item dropdown has-arrow new-user-menus">
						<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
							<span class="user-img">
								<img class="rounded-circle" src="{{url('/public/storage/settings/appicon.png')}}" width="31" alt="{{Auth::guard('admin')->user()->name??''}}">
								<div class="user-text">
									<h6>{{Auth::guard('admin')->user()->name??''}}</h6>
									<p class="text-muted mb-0">Administrator</p>
								</div>
							</span>
						</a>
						<div class="dropdown-menu">
							<div class="user-header">
								<div class="avatar avatar-sm">
									<img src="{{url('/public/storage/settings/appicon.png')}}" alt="User Image" class="avatar-img rounded-circle">
								</div>
								<div class="user-text">
									<h6>{{Auth::guard('admin')->user()->name??''}}</h6>
									<p class="text-muted mb-0">Administrator</p>
								</div>
							</div>
							<a class="dropdown-item" href="{{route('admin.profile')}}">My Profile</a>
							<!-- <a class="dropdown-item" href="inbox.html">Inbox</a> -->
							<a class="dropdown-item" href="{{url('admin/logout')}}">Logout</a>
						</div>
					</li>

				</ul>

			</div>


			<div class="sidebar" id="sidebar">
				<div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							

							<li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME) echo "active"?>">
								<a href="{{url('/admin')}}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME) echo "active"?>"><i class="feather-grid"></i> <span>Dashboard</span></a>
							</li>

							@if(CustomHelper::isAllowedModule('exam_categories') && CustomHelper::isAllowedSection('exam_categories' , 'list')  || CustomHelper::isAllowedModule('courses') && CustomHelper::isAllowedSection('courses' , 'list')|| CustomHelper::isAllowedModule('types') && CustomHelper::isAllowedSection('types' , 'list') || CustomHelper::isAllowedModule('types') && CustomHelper::isAllowedSection('types' , 'list') || CustomHelper::isAllowedModule('news') && CustomHelper::isAllowedSection('news' , 'list') || CustomHelper::isAllowedModule('banners') && CustomHelper::isAllowedSection('banners' , 'list'))

							<li class="submenu <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'exam_categories' || $url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'courses' || $url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'types' || $url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'subcategories' || $url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'banners' || $url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'news' ) echo "active"?>">

								<a href="#"><i class="fas fa-graduation-cap"></i> <span>Master</span> <span class="menu-arrow"></span></a>
								<ul >

									@if(CustomHelper::isAllowedModule('banners'))
									@if(CustomHelper::isAllowedSection('banners' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.banners.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'banners' ) echo "active"?>">Banner</a></li>
									@endif
									@endif

									@if(CustomHelper::isAllowedModule('exam_categories'))
									@if(CustomHelper::isAllowedSection('exam_categories' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.exam_categories.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'exam_categories' ) echo "active"?>">Exam Categories</a></li>
									@endif
									@endif


									@if(CustomHelper::isAllowedModule('subcategories'))
									@if(CustomHelper::isAllowedSection('subcategories' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.subcategories.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'subcategories' ) echo "active"?>">Exam </a></li>
									@endif
									@endif

									@if(CustomHelper::isAllowedModule('types'))
									@if(CustomHelper::isAllowedSection('types' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.types.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'types' ) echo "active"?>">Modules</a></li>
									@endif
									@endif


									@if(CustomHelper::isAllowedModule('courses'))
									@if(CustomHelper::isAllowedSection('courses' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.courses.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'courses' ) echo "active"?>">Courses</a></li>
									@endif
									@endif
									@if(CustomHelper::isAllowedModule('subjects'))
									@if(CustomHelper::isAllowedSection('subjects' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.subjects.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'subjects' ) echo "active"?>">Subject</a></li>
									@endif
									@endif

									@if(CustomHelper::isAllowedModule('chapters'))
									@if(CustomHelper::isAllowedSection('chapters' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.chapters.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'chapters' ) echo "active"?>">Chapters</a></li>
									@endif
									@endif


									@if(CustomHelper::isAllowedModule('contents'))
									@if(CustomHelper::isAllowedSection('contents' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.contents.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'contents' ) echo "active"?>">Contents</a></li>
									@endif
									@endif




									@if(CustomHelper::isAllowedModule('news'))
									@if(CustomHelper::isAllowedSection('news' , 'list'))
									<li><a href="{{ route($ADMIN_ROUTE_NAME.'.news.index') }}"  class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'news' ) echo "active"?>">News / Blogs</a></li>
									@endif
									@endif


								</ul>
							</li>

							@endif


							@if(CustomHelper::isAllowedModule('roles') && CustomHelper::isAllowedSection('roles' , 'list') || CustomHelper::isAllowedModule('permission') && CustomHelper::isAllowedSection('permission' , 'list'))

							<li class="submenu">
								<a href="#"><i class="fa fa-lock"></i> <span>Role & Permission</span> <span class="menu-arrow"></span></a>
								<ul>
									@if(CustomHelper::isAllowedModule('roles'))
									@if(CustomHelper::isAllowedSection('roles' , 'list'))
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.roles.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'roles' ) echo "active"?>">Roles</a></li>
									@endif
									@endif

									@if(CustomHelper::isAllowedModule('permission'))
									@if(CustomHelper::isAllowedSection('permission' , 'list'))
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.permission.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'permission' ) echo "active"?>">Permission</a></li>
									@endif
									@endif

								</ul>
							</li> 

							@endif


							@if(CustomHelper::isAllowedModule('countries') && CustomHelper::isAllowedSection('countries' , 'list') || CustomHelper::isAllowedModule('states') && CustomHelper::isAllowedSection('states' , 'list') || CustomHelper::isAllowedModule('cities') && CustomHelper::isAllowedSection('cities' , 'list'))

							<li class="submenu">
								<a href="#"><i class="fa fa-map-marker"></i> <span>Locations</span> <span class="menu-arrow"></span></a>
								<ul>
									@if(CustomHelper::isAllowedModule('countries'))
									@if(CustomHelper::isAllowedSection('countries' , 'list'))
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.countries.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'countries' ) echo "active"?>">Country List</a></li>
									@endif
									@endif

									@if(CustomHelper::isAllowedModule('states'))
									@if(CustomHelper::isAllowedSection('states' , 'list'))
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.states.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'states' ) echo "active"?>">State List</a></li>
									@endif
									@endif

									@if(CustomHelper::isAllowedModule('cities'))
									@if(CustomHelper::isAllowedSection('cities' , 'list'))
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.cities.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'cities' ) echo "active"?>">City List</a></li>
									@endif
									@endif
								</ul>
							</li> 

							@endif



							@if(CustomHelper::isAllowedModule('admins') && CustomHelper::isAllowedSection('admins' , 'list'))
							<li class="submenu <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'admins' ) echo "active"?>">
								<a href="#"><i class="fas fa-chalkboard-teacher"></i> <span> Admins</span> <span class="menu-arrow"></span></a>
								<ul>
									@if(CustomHelper::isAllowedModule('admins'))
									@if(CustomHelper::isAllowedSection('admins' , 'list'))
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.admins.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'admins' ) echo "active"?>">Admin List</a></li>
									@endif
									@endif
									
								</ul>
							</li>
							@endif
							

							@if(CustomHelper::isAllowedModule('user') && CustomHelper::isAllowedSection('user' , 'list'))
							<li class="submenu <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'user' ) echo "active"?>">
								<a href="#"><i class="fas fa-user"></i> <span> Users</span> <span class="menu-arrow"></span></a>
								<ul>
									@if(CustomHelper::isAllowedModule('user'))
									@if(CustomHelper::isAllowedSection('user' , 'list'))
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.user.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'user' ) echo "active"?>">User List</a></li>
									@endif
									@endif
								</ul>
							</li>
							@endif

							@if(CustomHelper::isAllowedModule('exams') && CustomHelper::isAllowedSection('exams' , 'list'))
							<li class="submenu <?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'exams' ) echo "active"?>">
								<a href="#"><i class="fas fa-chalkboard-teacher"></i> <span> Exams</span> <span class="menu-arrow"></span></a>
								<ul>
									@if(CustomHelper::isAllowedModule('exams'))
									@if(CustomHelper::isAllowedSection('exams' , 'list'))

									
									<li ><a href="{{ route($ADMIN_ROUTE_NAME.'.exams.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/'.'exams' ) echo "active"?>">Exams List</a></li>
									@endif
									@endif
									
								</ul>
							</li>
							@endif


							@if(CustomHelper::isAllowedModule('loan_requests') && CustomHelper::isAllowedSection('loan_requests' , 'list'))
							<li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/loan_requests') echo "active"?>">
								<a href="{{ route($ADMIN_ROUTE_NAME.'.loan_requests.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/loan_requests') echo "active"?>"><i class="fa fa-money" aria-hidden="true"></i><span>Loan Requests</span></a>
							</li>
							@endif

							@if(CustomHelper::isAllowedModule('subscription_history') && CustomHelper::isAllowedSection('subscription_history' , 'list'))
							<li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/subscription_history') echo "active"?>">
								<a href="{{ route($ADMIN_ROUTE_NAME.'.subscription_history.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/subscription_history') echo "active"?>"><i class="fa fa-money" aria-hidden="true"></i><span>Subscription History</span></a>
							</li>
							@endif


							@if(CustomHelper::isAllowedModule('transactions') && CustomHelper::isAllowedSection('transactions' , 'list'))
							<li class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/transactions') echo "active"?>">
								<a href="{{ route($ADMIN_ROUTE_NAME.'.transactions.index') }}" class="<?php if($url == $baseurl.'/'.$ADMIN_ROUTE_NAME.'/transactions') echo "active"?>"><i class="fa fa-exchange"></i> <span>Transactions</span></a>
							</li>
							@endif

							<li class="">
								<a href="{{url('/admin/logout')}}" class=""><i class="fa fa-sign-out"></i> <span>Logout</span></a>
							</li>

						</ul>
					</div>
				</div>
			</div>
			
			@yield('content')
			<footer>
				<p>Copyright © 2022 {{$settings->app_name ??'Tekniko Global Pvt Ltd.'}}.</p>
			</footer>

		</div>


		<script src="{{asset('public/assets/js/jquery-3.6.0.min.js')}}"></script>

		<script src="{{asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

		<script src="{{asset('public/assets/js/feather.min.js')}}"></script>

		<script src="{{asset('public/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

		<script src="{{asset('public/assets/plugins/apexchart/apexcharts.min.js')}}"></script>
		<script src="{{asset('public/assets/plugins/apexchart/chart-data.js')}}"></script>

		<script src="{{asset('public/assets/js/script.js')}}"></script>

		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




	</body>

	</html>


	<script type="text/javascript">
		$(document).ready(function() {
			$('.select2').select2();
		});
	</script>

	<script type="text/javascript">
		$('#state_id').on('change', function()
		{

			var _token = '{{ csrf_token() }}';
			var state_id = $('#state_id').val();
			$.ajax({
				url: "{{ route('get_city') }}",
				type: "POST",
				data: {state_id:state_id},
				dataType:"HTML",
				headers:{'X-CSRF-TOKEN': _token},
				cache: false,
				success: function(resp){
					$('#city_id').html(resp);
				}
			});
		});

		$('#country_id').change( function()
		{

			var _token = '{{ csrf_token() }}';
			var country_id = $('#country_id').val();
			$.ajax({
				url: "{{ route('get_state') }}",
				type: "POST",
				data: {country_id:country_id},
				dataType:"HTML",
				headers:{'X-CSRF-TOKEN': _token},
				cache: false,
				success: function(resp){
					$('#state_id').html(resp);
				}
			});
		});

	</script>

	<script type="text/javascript">
		function set_tab_in_session(key){
			var _token = '{{ csrf_token() }}';
			$.ajax({
				url: "{{ route('admin.set_tab_in_session') }}",
				type: "POST",
				data: {key:key},
				dataType:"HTML",
				headers:{'X-CSRF-TOKEN': _token},
				cache: false,
				success: function(resp){
					
				}
			});
		}
	</script>

	<script>
    $('#category_id').on('change', function() {
        // var category_id = this.value;
        category_id = $('#category_id option:selected').toArray().map(item => item.value).join();
    
        var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($ADMIN_ROUTE_NAME.'.get_sub_category') }}",
                type: "POST",
                data: {category_id:category_id},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    $('#subcategory_id').html(resp);
                }
            });
    });


    function get_subjects(course_id){
    	var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($ADMIN_ROUTE_NAME.'.get_subjects') }}",
                type: "POST",
                data: {course_id:course_id},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    $('#subject_id').html(resp);
                }
            });

    }


   function get_chapter(subject_id){

        var _token = '{{ csrf_token() }}';

            $.ajax({
                url: "{{ route($ADMIN_ROUTE_NAME.'.get_chapter') }}",
                type: "POST",
                data: {subject_id:subject_id},
                dataType:"HTML",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    $('#chapter_id').html(resp);
                    $('#chapter_id1').html(resp);
                }
            });
    }



    CKEDITOR.replace('description');
</script>