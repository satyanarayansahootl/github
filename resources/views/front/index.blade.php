@extends('front.common.layout')

@section('content')

<?php 
$home_cats = config('custom.homecats');

?>

<!--*********************** Hero banner  Start****************************** -->
<div class="mt-5" style="margin-top: 7rem!important;padding-left: 30px;   padding-right: 30px;">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
        <div class="carousel-indicators">
           <?php if(!empty($banners)){
             for ($i=0; $i < count($banners); $i++) { 
                ?>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{$i}}" class="<?php if($i == 0)echo "active";?>"
                    aria-current="true" aria-label="Slide 1"></button>
                <?php }}?>


            </div>
            <div class="carousel-inner">
                <?php if(!empty($banners)){
                    $i = 0;
                    foreach($banners as $banner){
                        ?>
                        <div class="carousel-item <?php if($i == 0)echo "active";?>">
                            <img src="{{CustomHelper::getImageUrl('banners', $banner->image)}}" class="d-block w-100" alt="...">
                        </div>
                        <?php $i++;}}?>

                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!--*********************** Hero banner  End****************************** -->
    <section class="border py-5 bg-light">
        <div class="container">
            <div class="d-flex quizbanner justify-content-around align-items-center">
                <?php if(!empty($home_cats)){
                    foreach ($home_cats as $key => $value) {
                        $url ='';
                        if($value['slug'] == 'blogs' || $value['slug'] == 'current-affairs'){
                            $url = url('/'.$value['slug']);
                        }
                        ?>
                        <div class="col-6 col-md-2  text-center">
                            <a href="{{$url}}">
                                <div
                                class="bg-white mx-auto  shadow-sm border border-danger d-flex justify-content-center align-items-center p-4 rounded-pill sp-features ">
                                <img src="{{$value['image']??''}}" alt="" class="img-fluid">
                            </div>
                            <p class="m-0 mt-3 text-dark">{{$value['name']??''}}</p>
                        </a>
                    </div>
                <?php }}?>



            </div>
        </div>
    </section>
    <!--********************************* Courses by Exam********************************* -->
    <section class="py-5">
        <div class="container">
            <div class="sec-heading d-flex justify-content-between align-items-center mb-3">
                <h4>Courses by Exam</h4>
                <a href="{{url('/courses')}}"
                class="text-white px-2 py-1 bg-theme rounded d-flex justify-content-center align-items-center">View
            all</a>
        </div>
        <div class="row g-3">
            <?php if(!empty($course_by_exam)){
                foreach($course_by_exam as $course_by){
                    ?>
                    <div class="col-6 col-md-3 col-lg-3 ">
                        <a href="{{route('courses',['type_name'=>'subcategory','type'=>$course_by->slug])}}" class="text-center bg-light shadow-sm d-block p-2">
                            <div
                            class="bg-white mx-auto  shadow-sm  d-flex justify-content-center align-items-center p-4 rounded-pill sp-features ">
                            <img src="{{CustomHelper::getImageUrl('subcategory', $course_by->image)}}" alt="" class="img-fluid" height="100%" width="100%">
                        </div>
                        <p class="m-0 mt-3">{{$course_by->name??''}}</p>
                    </a>
                </div>
            <?php }}?>

        </div>

    </div>
</section>
<!--********************************* Find Courses by Types******************************** -->
<section class="py-5 border bg-light">
    <div class="container">
        <div class="sec-heading d-flex justify-content-between align-items-center mb-3">
            <h4>Find Courses by Types</h4>
            <!-- <a href="{{url('/courses')}}" 
            class="text-white px-2 py-1 bg-theme rounded d-flex justify-content-center align-items-center">View
        all</a>-->
    </div>
    <div class="row sliders">
        <?php if(!empty($course_by_type)){
            foreach($course_by_type as $course_type){
                ?>
                <div class="col-6 col-md-3 col-lg-3 ">
                    <a href="{{route('courses',['type_name'=>'modules','type'=>$course_type->slug])}}" class="text-center bg-white shadow-sm d-block p-2 ">
                        <div
                        class="shadow-sm  d-flex justify-content-center align-items-center p-4 border border-danger rounded">
                        <img src="{{CustomHelper::getImageUrl('types', $course_type->image)}}" alt="" class="img-fluid" style="height: 100px; width:100%;">
                    </div>
                    <p class="m-0 mt-3">{{$course_type->name??''}}</p>
                </a>
            </div>
        <?php }}?>

    </div>
</div>
</section>

<!--********************************* Top Course********************************* -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="sec-heading d-flex justify-content-between align-items-center mb-5">
            <h4>Top Course</h4>
            <a href="{{url('/courses')}}"
            class="px-2 py-1 text-white bg-theme rounded d-flex justify-content-center align-items-center">View
        all</a>
    </div>

    <div class="row ourstar">
        <?php if(!empty($top_courses)){
            foreach($top_courses as $top_course){
                ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="" class="rounded d-block shadow-sm bg-white">
                        <div class="top-img position-relative">
                            <img src="{{CustomHelper::getImageUrl('course', $top_course->image)}}" alt="" class="img-fluid rounded">
                            <div class="top-content  position-absolute start-0 bottom-0  text-center">
                                <p class="text-white">Bank & Insurance Yearly Subscription Online Test Series 2022-23
                                </p>
                            </div>
                        </div>
                        <div class="top-course-detail p-3 text-start" style="height:150px;">
                            <h6 class="fs-5">Course Highlights: </h6>
                                <p>{!!$top_course->course_highlights??''!!}</p>
                                </div>
                                <div class="d-flex align-items-center p-3 justify-content-between">
                                    <span><del>₹{{$top_course->mrp}}</del></span>
                                    <span class="ms-3 t-green">₹{{$top_course->price}} <span class="fs-10">50%off</span></span>
                                    <button class="btn px-3 text-white green">View</button>
                                </div>
                            </a>
                        </div>
                    <?php }}?>


                </div>
            </div>
        </section>

        <!--********************************* Latest Posts********************************* -->
        <section class="py-5 bg-light">
            <div class="container">
                <div class="sec-heading d-flex justify-content-between align-items-center mb-5">
                    <h4>Latest Posts</h4>
                    <a href="{{url('/courses')}}"
                    class="px-2 py-1 text-white bg-theme rounded d-flex justify-content-center align-items-center">View
                all</a>
            </div>

            <div class="row ">
                <div class="col-12 col-md-6  ">
                    <a href="" class="p-4 d-block border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-dark fs-15 m-0">Article</p>
                                <p class="fs-10">One Hour ago</p>
                            </div>
                            <div>
                                <img src="{{asset('public/web_assets/image/Vector.png')}}" alt="" class="img-fluid " width="20">
                            </div>
                        </div>
                        <div class="blog-latest-img">
                            <img src="{{asset('public/web_assets/image/image 1.png')}}" alt="" class="img-fluid">
                        </div>
                        <div class="mt-2">
                            <h6 class="p-0">The Hindu Editorial Analysis: 12th October 2022</h6>
                            <p class="fs-13">Chronology based, related to freedom movement
                                Key personalities of freedom movement and related
                                stuff like organisations they had set-up, positions
                                of importance & key movements
                            Reforms and step taken by British... Read More</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <samp><i class="bi bi-hand-thumbs-up me-1"></i>36 <span>Likes</span></samp>
                                <span><i class="bi bi-chat-left-text me-1"></i>56 <span>Comments</span></span>
                            </div>
                            <div class=" mt-3 border rounded-pill ">
                                <textarea class="form-control bg-light" id="exampleFormControlTextarea1"></textarea>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6  ">
                    <div class="blog-post">
                        <a href="" class="p-2 d-block border mb-3">
                            <div class="row g-3 flex-column flex-md-row align-items-center">
                                <div class="col-12 col-md-6">
                                    <div>
                                        <img src="{{asset('public/web_assets/image/image 1.png')}}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div>
                                        <h6 class="p-0">The Hindu Editorial Analysis: 12th October 2022</h6>
                                        <p class="fs-13">Chronology based, related to freedom movement
                                            Key personalities of freedom movement and related
                                            stuff like organisations they had set-up, positions
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </a>

                        <a href="" class="p-2 d-block border mb-3">
                            <div class="row g-3 flex-column flex-md-row align-items-center">
                                <div class="col-12 col-md-6">
                                    <div>
                                        <img src="{{asset('public/web_assets/image/image 1.png')}}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div>
                                        <h6 class="p-0">The Hindu Editorial Analysis: 12th October 2022</h6>
                                        <p class="fs-13">Chronology based, related to freedom movement
                                            Key personalities of freedom movement and related
                                            stuff like organisations they had set-up, positions
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </a>

                        <a href="" class="p-2 d-block border mb-3">
                            <div class="row g-3 flex-column flex-md-row align-items-center">
                                <div class="col-12 col-md-6">
                                    <div>
                                        <img src="{{asset('public/web_assets/image/image 1.png')}}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div>
                                        <h6 class="p-0">The Hindu Editorial Analysis: 12th October 2022</h6>
                                        <p class="fs-13">Chronology based, related to freedom movement
                                            Key personalities of freedom movement and related
                                            stuff like organisations they had set-up, positions
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </a>

                        <a href="" class="p-2 d-block border mb-3">
                            <div class="row g-3 flex-column flex-md-row align-items-center">
                                <div class="col-12 col-md-6">
                                    <div>
                                        <img src="{{asset('public/web_assets/image/image 1.png')}}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div>
                                        <h6 class="p-0">The Hindu Editorial Analysis: 12th October 2022</h6>
                                        <p class="fs-13">Chronology based, related to freedom movement
                                            Key personalities of freedom movement and related
                                            stuff like organisations they had set-up, positions
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </a>

                        <a href="" class="p-2 d-block border mb-3">
                            <div class="row g-3 flex-column flex-md-row align-items-center">
                                <div class="col-12 col-md-6">
                                    <div>
                                        <img src="{{asset('public/web_assets/image/image 1.png')}}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div>
                                        <h6 class="p-0">The Hindu Editorial Analysis: 12th October 2022</h6>
                                        <p class="fs-13">Chronology based, related to freedom movement
                                            Key personalities of freedom movement and related
                                            stuff like organisations they had set-up, positions
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </a>

                        <a href="" class="p-2 d-block border mb-3">
                            <div class="row g-3 flex-column flex-md-row align-items-center">
                                <div class="col-12 col-md-6">
                                    <div>
                                        <img src="{{asset('public/web_assets/image/image 1.png')}}" alt="" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div>
                                        <h6 class="p-0">The Hindu Editorial Analysis: 12th October 2022</h6>
                                        <p class="fs-13">Chronology based, related to freedom movement
                                            Key personalities of freedom movement and related
                                            stuff like organisations they had set-up, positions
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--************************** Quize Listing********************** -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="sec-heading d-flex justify-content-between align-items-center mb-3">
            <h4>Daily Quiz</h4>
            <a href=""
            class="text-white px-2 py-1 bg-theme rounded d-flex justify-content-center align-items-center">View
        all</a>
    </div>
    <div class="row g-3  quiz">
        <?php if(!empty($daily_quiz)){
            foreach($daily_quiz as $daily){
            ?>

        <div class="col-12 col-lg-3">
            <a href="freequiz.html" class="d-block  bg-white shadow-sm p-3 rounded">
                <div class="d-flex flex-column ">
                    <div class="col-12">
                        <div class=" text-center quiz-icon p-3">
                            <img src="{{asset('public/web_assets/image/watch.png')}}" alt="Free Quiz" class="img-fluid mx-auto"
                            width="150">
                        </div>
                    </div>
                    <div class="col-12  mt-3">
                        <h4 class="free-quiz-heading fs-19 max_line1">{{$daily->title??''}}</h4>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="ms-1 m-0 fs-12">Ques : <span>5</span></p>
                                <p class="ms-1 m-0 fs-12">Duration : <span>5 Mins </span></p>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-theme mt-3 rounded-pill">Start Quiz<i
                                class="bi bi-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php }}?>

            
        </div>
    </div>
</section>

<!--************************** Latest Blogs********************** -->
<section class="py-5 bg-light border">
    <div class="container">
        <div class="sec-heading  mb-5">
            <h4 class="listing-heading  mb-3">Latest Blogs</h4>
        </div>
        <div class="row g-3 ">
             <?php if(!empty($latest_blogs)){
            foreach($latest_blogs as $latest){


            ?>
            <div class="col-12 col-lg-6">
                <a href="" class="d-block free-quiz-box bg-white shadow-sm p-3 rounded">
                    <div class="d-flex align-items-center ">
                        <div class=" 3">
                            <img src="{{CustomHelper::getImageUrl('news', $latest->image)}}" alt="blogimg" class="img-fluid rounded" width="150" style="height: 100px;">
                        </div>
                        <div class="col-9 ms-3">
                            <p class="fs-10 m-0">{{ucfirst(CustomHelper::getPastTime($latest->created_at))}}</p>
                            <h4 class="free-quiz-heading fs-19">{{$latest->title??''}} ({{date('M d, Y',strtotime($latest->date))}})</h4>
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-stopwatch"></i>
                                    <p class="ms-1 m-0 fs-12  text-nowrap">{{date('F d,Y')}}</p>

                                </div>
                                <?php if(!empty($latest->author_name)){?>
                                <div class="d-flex align-items-center ms-2 ms-sm-3">
                                    <i class="bi bi-person"></i>
                                    <p class="ms-1 m-0 fs-12  text-nowrap">{{$latest->author_name??''}}</p>

                                </div>
                            <?php }?>
                                <div class="d-flex align-items-center ms-2 ms-sm-3">
                                    <i class="bi bi-chat-dots"></i>
                                    <p class="ms-1 m-0 fs-12  text-nowrap">{{CustomHelper::getNewsCommentCount($latest->id)}}</p>

                                </div>
                            </div>

                        </div>
                    </div>
                </a>
            </div>
        <?php }}?>

            
        </div>
        <div class="sec-heading d-flex justify-content-end mb-5">
            <a href="{{route('blogs')}}"
            class="text-decoration-underline  text-danger  d-flex justify-content-center align-items-center">Read
        more Blogs</a>
    </div>
</div>
</section>
<!--*******************************  Testimonial/Achiver  *******************************  -->
<section class="bg-white py-5 ">
    <div class="container">
        <div class="text-center mb-4">
            <h6>Testimonial</h6>
            <h4 class="fw-bold fs-4">Our Achievers Take</h4>
        </div>
        <div class="row ourstar">

            <?php if(!empty($our_achievers)){
            foreach($our_achievers as $our_achieve){
            ?>
            <div class="col-6 col-lg-4">
                <a href="" class="">
                    <div class="start-active text-center p-4">
                        <i class="bi bi-quote fs-3"></i>
                        <p class="fs-13">{!!$our_achieve->description??''!!}</p>
                    </div>
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="achiver-icon">
                            <img src="{{CustomHelper::getImageUrl('our_achievers', $our_achieve->image)}}" alt="" class="img-fluid">
                        </div>
                        <p class="m-0">{{$our_achieve->name??''}}</p>
                        <span>{{$our_achieve->title??''}}</span>
                    </div>
                </a>
            </div>
        <?php }}?>
            

        </div>
    </div>
</section>
<!-- *************************** Counter ************************************* -->
<section class="bg-light py-5 ">
    <div class="container">
        <div class="d-flex counter-scroll  justify-content-center justify-content-md-between align-items-center">
            <div class="col-6 col-md-2">
                <div class="d-flex flex-column align-items-center bg-white shadow-sm p-4">
                    <div class="counter-img d-flex justify-content-center align-items-center rounded-circle">
                        <img src="{{asset('public/web_assets/image/Vectors.png')}}" alt="vector" class="img-fluid" width="25" height="25">
                    </div>
                    <h2 class="count fs-19 m-0">{{$trusted['trusted_students']??''}}</h2>
                    <h4 class="fs-12"><span>Lakhs</span></h4>
                    <h4 class="fs-12"> Trusted Student</h4>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="d-flex flex-column align-items-center bg-white shadow-sm p-4">
                    <div class="counter-img d-flex justify-content-center align-items-center rounded-circle">
                        <img src="{{asset('public/web_assets/image/Vector-1.png')}}" alt="vector" class="img-fluid" width="25" height="25">
                    </div>
                    <h2 class="count fs-19 m-0">{{$trusted['selections_students']??''}}</h2>
                    <h4 class="fs-12"><span>Lakhs</span></h4>
                    <h4 class="fs-12"> Selections</h4>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="d-flex flex-column align-items-center bg-white shadow-sm p-4">
                    <div class="counter-img d-flex justify-content-center align-items-center rounded-circle">
                        <img src="{{asset('public/web_assets/image/Vector-2.png')}}" alt="vector" class="img-fluid" width="25" height="25">
                    </div>
                    <h2 class="count fs-19 m-0">{{$trusted['success_rate']??''}}</h2>
                    <h4 class="fs-12"><span>Lakhs</span></h4>
                    <h4 class="fs-12">Success Rate</h4>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="d-flex flex-column align-items-center bg-white shadow-sm p-4">
                    <div class="counter-img d-flex justify-content-center align-items-center rounded-circle">
                        <img src="{{asset('public/web_assets/image/Vector-3.png')}}" alt="vector" class="img-fluid" width="25" height="25">
                    </div>
                    <h2 class="count fs-19 m-0">{{$trusted['total_test_attempt']??''}}</h2>
                    <h4 class="fs-12"><span>Crore</span></h4>
                    <h4 class="fs-12">Total Test Attempted </h4>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- *************************** Get Strated ************************************* -->
<section class="">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9">
                <div
                class="get-started d-flex justify-content-md-between justify-content-center align-items-center flex-column flex-md-row p-md-5">
                <h6 class="text-white">Take your first test free!</h6>
                <div class="d-flex align-items-center">
                    <h6 class="me-3 ">Let's Do it! -</h6>
                    <a href="" class="text-white px-3 py-1 bg-gradients  ">Get Started</a>
                </div>
            </div>
        </div>


    </div>
</div>
</section>

<!-- ************************** Footer Section ******************************** -->
@endsection