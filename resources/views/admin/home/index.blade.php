@extends('admin.layouts.layouts')
<?php 
$settings = \App\Models\Settings::first();
?>

@section('content')

<div class="page-wrapper">
	<div class="content container-fluid">

		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<div class="page-sub-header">
						<h3 class="page-title">Welcome {{Auth::guard('admin')->user()->name??''}}!</h3>

						

						<ul class="breadcrumb">
							<li class="breadcrumb-item active">Home</li>
						</ul>
					</div>
				</div>
			</div>
		</div>






		
		<form action="" method="post" enctype="multipart/form-data" style="display:none;">
			@csrf
			<div class="student-group-form">
				<div class="row">

					<div class="col-lg-4 col-md-6">
						<div class="form-group">
							<input type="file" name="file"  class="form-control" placeholder="Search by ID ...">
						</div>
					</div>



					<div class="col-lg-4 col-md-6">
						<div class="form-group">
							<input type="date" name="start_date" value="{{$start_date??''}}" class="form-control" placeholder="Search by ID ...">
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="form-group">
							<input type="date" name="end_date" value="{{$end_date??''}}" class="form-control" placeholder="Search by Name ...">
						</div>
					</div> 
					<div class="col-lg-4">
						<div class="search-student-btn">
							<button type="btn" class="btn btn-primary">Search</button>
						</div>
					</div>
				</div>
			</div>
		</form>

		<div class="row mt-4">
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a href="{{route('admin.user.index')}}">
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Students</h6>
									<h3>{{$users??0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-01.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a href="{{route('admin.courses.index')}}">
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Courses</h6>
									<h3>{{$course??0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-02.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a href="">
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Exams</h6>
									<h3>30+</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-03.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card bg-comman w-100">
					<a href="{{route('admin.transactions.index')}}">
						<div class="card-body">
							<div class="db-widgets d-flex justify-content-between align-items-center">
								<div class="db-info">
									<h6>Revenue</h6>
									<h3>₹{{$revenue??0}}</h3>
								</div>
								<div class="db-icon">
									<img src="{{asset('public/assets/img/icons/dash-icon-04.svg')}}" alt="Dashboard Icon">
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-lg-12">

				<div class="card card-chart">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col-12">
								<h5 class="card-title">Student & Subscription</h5>
							</div>
							<div class="col-12">
								<ul class="chart-list-out">

									<li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div id="apexcharts-area"></div>
					</div>
				</div>

			</div>
						<!-- <div class="col-md-12 col-lg-6">

							<div class="card card-chart">
								<div class="card-header">
									<div class="row align-items-center">
										<div class="col-6">
											<h5 class="card-title">Number of Students</h5>
										</div>
										<div class="col-6">
											<ul class="chart-list-out">
											<li><span class="circle-blue"></span>Girls</li>
												<li><span class="circle-green"></span>Boys</li> 
												<li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div id="bar"></div>
								</div>
							</div>

						</div> -->
					</div>
					

				</div>

				<script type="text/javascript">
					$(document).ready(function () {
						if ($("#apexcharts-area").length > 0) {
							var options = {
								chart: { height: 350, type: "line", toolbar: { show: false } },
								dataLabels: { enabled: false },
								stroke: { curve: "smooth" },
								series: [
									{ name: "Students", color: "#ef151e", data: [

									 	// 45, 60, 75, 51, 42, 42, 30,75, 51, 42, 42, 30
										<?php 
										$year = date('Y');

										for ($i = 1; $i <= 12; $i++) {
											$sub_count = \App\Models\User::where('is_delete',0);
											if(!empty($start_date)){
												$sub_count->whereDate('created_at','>=',$start_date);
											}
											if(!empty($end_date)){
												$sub_count->whereDate('created_at','<=',$end_date);
											}else{
												$sub_count->whereMonth('created_at',$i)->whereYear('created_at',$year);
											}

											$sub_count = $sub_count->count();
											?>
											"<?php echo $sub_count?>",

										<?php } ?>

										] },
									{ name: "Subscription", color: "#70C4CF", data: [

										<?php 
										$year = date('Y');
										for ($i = 1; $i <= 12; $i++) {

											$sub_count = 0;
											?>
											"<?php echo $sub_count?>",
										<?php } ?>

										] },
									],
								xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",'Aug','Sep','Oct','Nov',"Dec"] },
							};
							var chart = new ApexCharts(document.querySelector("#apexcharts-area"), options);
							chart.render();
						}

					});
				</script>
				@endsection