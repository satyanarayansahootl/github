@extends('front.common.layout')

@section('content')

<?php 
$home_cats = config('custom.homecats');
$types = DB::table('course_types')->where('course_id', $course->id)->get();

$exam_covered = [];

$subcategory_id = $course->subcategory_id??'';
if(!empty($subcategory_id)){
    $subcategory_id = explode(",", $subcategory_id);
    if(!empty($subcategory_id)){
        foreach($subcategory_id as $key=>$value){
            $cat = \App\Models\SubCategory::where('id',$value)->first();
            if(!empty($cat->image)){
                $cat->image = CustomHelper::getImageUrl('subcategory',$cat->image);
            }
            $exam_covered[] = $cat;
        }
    }
}


$total_rating = DB::table('ratings')->where('course_id',$course->id)->count();
$total_sum_rating = DB::table('ratings')->where('course_id',$course->id)->sum('rating');
if($total_rating > 0){
    $avg_ratings = $total_sum_rating / $total_rating;
    $avg_ratings = number_format((float)$avg_ratings, 1, '.', '');
}else{
    $avg_ratings = 0;
}


$ratings = \App\Models\Rating::where('course_id',$course->id)->latest()->first();

$user = \App\Models\User::where('id',$ratings->user_id)->first();
$ratings->user_name = $user->name??'';

// $ratings->rating = (int)$ratings->rating;
$ratings->image = CustomHelper::getImageUrl('user',$user->profile_picture);
?>

<div class="sec-marign py-5 bg-light">
    <div class="container">
        <div class="row g-3  ">
            <div class="col-12 col-md-8">
                <?php if(!empty($course->intro_video)){?>
                <!-- <iframe width="100%" height="500px" 
                    src="https://www.youtube.com/embed/{{$course->intro_video}}?rel=0&amp;controls=1&amp&amp;showinfo=0&amp;modestbranding=1">
                </iframe> -->
               <iframe class="embed-responsive-item"id="ytplayer" type="text/html" width="640" height="360" src="https://www.youtube.com/embed/M7lc1UVf-VE?&autoplay=1&loop=1&rel=0&showinfo=0&color=white&iv_load_policy=3-VE"
                    frameborder="0" allowfullscreen></iframe>

                
            <?php }else{?>
                <img src="{{CustomHelper::getImageUrl('course',$course->image)}}" width="100%" height="500px">
            <?php }?>
            <div class="d-flex align-items-center justify-content-between mb-3 mt-3">
                <?php 

                if (!empty($types)) {
                    foreach ($types as $type) {
                        $type = \App\Models\Types::where('id', $type->type_id)->first();
                        if (!empty($type)) {
                            ?>
                            <span class="px-3 py-1 bg-info text-white rounded">
                                <img src="{{CustomHelper::getImageUrl('types',$type->icon);}}" alt="" height="20px" width="20px"> {{$type->name??''}}</span>
                            <?php }}}?>


                            <h6 class="t-green">Validity: {{$course->duration??''}} {{$course->type??''}}</h6>

                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="" class="btn btn-theme">Talk to Us</a>
                            <a href="" class="btn green text-white">Buy Now</a>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class=" ">
                            <h2 class="fs-4">Course Highlights: </h2>
                            <ul class="">
                                {!!$course->course_highlights??''!!}
                            </ul>
                        </div>
                        <!-- description -->
                        <div class="row g-3">
                            <div class="col-12">
                                <a href="#" class="bg-theme d-flex justify-content-between p-1"> 
                                    <p  class="m-0 fw-bold text-white">View Full Description</p> 
                                    <i class="bi bi-caret-right-fill text-white"></i> 
                                </a>
                            </div> 
                            <div class="row g-3">
                                <?php 
                                if (!empty($types)) {
                                    foreach ($types as $type) {
                                        $type = \App\Models\Types::where('id', $type->type_id)->first();
                                        if (!empty($type)){
                                            $count = 1;
                                            ?>
                                            <div class="col-12">
                                                <div class="p-1 bg-white rounded shadow-sm d-block">
                                                    <div class="row align-items-center">
                                                        <div class="col-2">
                                                            <div class="de-icon">
                                                                <img src="{{CustomHelper::getImageUrl('types',$type->icon);}}" alt="" width="30">
                                                            </div>
                                                        </div>
                                                        <div class="col-5">
                                                            <div class="color-dark d-flex">
                                                                <p class="m-0">{{$count}} {{$type->name??''}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-5 text-end">
                                                            <i class="bi bi-caret-right-fill"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }}}?>

                                    </div> 
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <section class="py-5">
                    <div class="container">
                        <h5 class="fw-bold">Exam Covered</h5>
                        <div class="row g-3">
                            <?php if(!empty($exam_covered)){
                                foreach($exam_covered as $exam){
                                    ?>
                                    <div class="col-6 col-md-3 col-lg-2">
                                        <a href="" class="text-center   d-block p-2">
                                            <div
                                            class=" mx-auto   d-flex justify-content-center align-items-center p-4 rounded-pill sp-features ">
                                            <img src="{{$exam->image??''}}" alt="" class="img-fluid" height="100%" width="100%">
                                        </div>
                                        <p class="m-0 mt-2">{{$exam->name??''}}</p>
                                    </a>
                                </div>
                            <?php }}?>


                        </div>
                    </div>
                </section>
                <!-- Rating and Review -->
                <section class="py-5">
                    <div class="container">
                        <h5 class="fw-bold">Ratings & Reviews</h5>
                        <div class="row g-3 align-items-center">
                            <div class="col-6 col-md-3 col-lg-2">
                                <div>
                                    <span class="fs-3 fw-bold">{{$avg_ratings??0}}</span> 
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-fill fs-4"></i><p class="fs-12 m-0 fw-bold">{{$total_rating??0}} <span>Total</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 col-lg-3">
                                <div class="d-flex mb-2 justify-content-end">
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i> 
                                </div>
                                <div class="d-flex mb-2 justify-content-end">
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>

                                </div>
                                <div class="d-flex mb-2 justify-content-end">
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>  
                                    <i class="bi bi-star-fill text-warning mx-1"></i>  
                                </div>
                                <div class="d-flex mb-2 justify-content-end">
                                    <i class="bi bi-star-fill text-warning mx-1"></i>
                                    <i class="bi bi-star-fill text-warning mx-1"></i>

                                </div>
                                <div class="d-flex mb-2 justify-content-end">
                                    <i class="bi bi-star-fill text-warning mx-1"></i> 
                                </div>
                            </div>
                            <div class="col-6 col-md-3 col-lg-6">
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" role="progressbar" aria-label="Success example"
                                    style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-info" role="progressbar" aria-label="Info example"
                                    style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-warning" role="progressbar" aria-label="Warning example"
                                    style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-danger" role="progressbar" aria-label="Danger example"
                                    style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-danger" role="progressbar" aria-label="Danger example"
                                    style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-md-6">

                                <div class="review">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="review-user-img border">
                                                <img src="{{$ratings->image??''}}" alt="">
                                            </div>

                                            <div class="review-user-name ms-2">
                                                <span>{{$ratings->user_name??''}}</span>
                                                <p>
                                                    <?php if($ratings->rating >= 1){?>
                                                     <i class="bi bi-star-fill text-warning mx-1"></i>
                                                 <?php }if($ratings->rating >= 2){?>
                                                     <i class="bi bi-star-fill text-warning mx-1"></i>
                                                 <?php }if($ratings->rating >= 3){?>
                                                     <i class="bi bi-star-fill text-warning mx-1"></i>
                                                 <?php }if($ratings->rating >= 4){?>
                                                     <i class="bi bi-star-fill text-warning mx-1"></i>
                                                 <?php }if($ratings->rating >= 5){?>
                                                     <i class="bi bi-star-fill text-warning mx-1"></i>
                                                 <?php }?>


                                             </p>
                                         </div>
                                     </div>
                                     <a href="#" class="text-end mt-2 text-decoration-underline">View Post</a>
                                 </div> 
                                 <div>
                                    <p class="fs-14">{{$ratings->review??''}}</p>
                                </div>
                            </div>



                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span> Rate this</span>
                                <div id="full-stars-example">
                                    <div class="rating-group  ">
                                        <input class="rating__input rating__input--none d-none" name="rating" id="rating-none" value="0" type="checkbox">
                                        <label aria-label="No rating" class="rating__label d-none" for="rating-none"><i class="rating__icon rating__icon--none fa fa-ban"></i></label>
                                        <label aria-label="1 star" class="rating__label" for="rating-1"><i class="rating__icon rating__icon--star bi bi-star-fill color-orange fs-13"></i></label>
                                        <input class="rating__input" name="rating" id="rating-1" value="1" type="radio">
                                        <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star bi bi-star-fill color-orange fs-13"></i></label>
                                        <input class="rating__input" name="rating" id="rating-2" value="2" type="radio">
                                        <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--star bi bi-star-fill color-orange fs-13"></i></label>
                                        <input class="rating__input" name="rating" id="rating-3" value="3" type="radio" checked="">
                                        <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--star bi bi-star-fill color-orange fs-13"></i></label>
                                        <input class="rating__input" name="rating" id="rating-4" value="4" type="radio">
                                        <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--star bi bi-star-fill color-orange fs-13"></i></label>
                                        <input class="rating__input" name="rating" id="rating-5" value="5" type="radio">
                                    </div>

                                </div>

                            </div>
                            <div class="input-group">
                                <textarea class="form-control" aria-label="With textarea" placeholder="Write Your Review"></textarea>
                            </div>
                            <div class="col-12 text-end"> 
                                <button type="submit" class="btn mt-2  btn-theme  text-white">Post</button>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <h3>FAQs</h3>
                            <div>
                                <?php if(!empty($faqs)){
                                    foreach($faqs as $faq){
                                        ?>
                                        <details>
                                            <summary>{{$faq->question??''}}</summary>
                                            <p>{!!$faq->answer??''!!}</p>
                                        </details>
                                    <?php }}?>
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

<style type="text/css">
    .ytp-watch-later-button{
        display: none;
    }
    .ytp-fullscreen-button .ytp-button{
        display: none;
    }
</style>
        @endsection