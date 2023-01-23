@extends('front.common.layout')

@section('content')

<?php 
$exam = Session::get('exam');
$news_types = config('custom.news_type');


$tab_keys = Session::get('tab_keys');

$type_value = $tab_keys['type']??'news';
$key_value = $tab_keys['key']??'all';




?>
<section class="py-5 bg-light sec-marign">

    <div class="container"> 

        <div class="row"> 
            <div class="col-12">
                <ul class="nav nav-pills  flex-nowrap mb-3 overflow-scroll coursescroll" id="pills-tab" role="tablist">
                    <?php if(!empty($news_types)){
                        $i = 0;
                        foreach($news_types as $key=>$value){
                            $active = '';
                            if($type_value =='news' && $key == $key_value){
                               $active = "active";
                            }else{
                            if($i == 0 && $active ==''){
                                // $active = "active";
                            }else{
                                $active = '';
                            }
                        }
                        ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link text-nowrap rounded {{$active}} border me-md-3 " id="pills-ibps-tab{{$key}}" data-bs-toggle="pill" data-bs-target="#pills-ibps{{$key}}" type="button" role="tab" aria-controls="pills-ibps" aria-selected="true"  onclick="save_tab_keys('news','{{$key}}')">{{$value??''}}</button>
                        </li>
                        <?php $i++;}}?>

                    </ul>
                </div>

                <div class="tab-content" id="pills-tabContent">
                    <!--  ibps Us section   -->
                    <?php if(!empty($news_types)){
                        $j = 0;
                        foreach($news_types as $key=>$value){
                            $active = '';
                            if($type_value =='news' && $key == $key_value){
                               $active = "active";
                           }else{
                            if($i == 0 && $active ==''){
                                // $active = "active";
                            }else{
                                $active = '';
                            }
                        }
                        $blogs = CustomHelper::getBlogs($key);
                        ?>
                        <div class="tab-pane fade show {{$active}}" id="pills-ibps{{$key}}" role="tabpanel" aria-labelledby="pills-ibps-tab{{$key}}" tabindex="0"> 
                            <div class="container"> 
                                <div class="row g-3 ">

                                    <?php if(!empty($blogs)){
                                        foreach($blogs as $blog){
                                            ?>
                                            <div class="col-12 col-lg-6">
                                             <a href="" class="d-block free-quiz-box bg-white shadow-sm p-3 rounded">
                                                <div class="d-flex align-items-center ">
                                                    <div class=" 3">
                                                        <img src="{{CustomHelper::getImageUrl('news', $blog->image)}}" alt="blogimg" class="img-fluid rounded" width="150" style="height: 100px;">
                                                    </div>
                                                    <div class="col-9 ms-3">
                                                        <p class="fs-10 m-0">{{ucfirst(CustomHelper::getPastTime($blog->created_at))}}</p>
                                                        <h4 class="free-quiz-heading fs-19">{{$blog->title??''}} ({{date('M d, Y',strtotime($blog->date))}})</h4>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bi bi-stopwatch"></i>
                                                                <p class="ms-1 m-0 fs-12  text-nowrap">{{date('F d,Y')}}</p>

                                                            </div>
                                                            <?php if(!empty($blog->author_name)){?>
                                                                <div class="d-flex align-items-center ms-2 ms-sm-3">
                                                                    <i class="bi bi-person"></i>
                                                                    <p class="ms-1 m-0 fs-12  text-nowrap">{{$blog->author_name??''}}</p>

                                                                </div>
                                                            <?php }?>
                                                            <div class="d-flex align-items-center ms-2 ms-sm-3">
                                                                <i class="bi bi-chat-dots"></i>
                                                                <p class="ms-1 m-0 fs-12  text-nowrap">{{CustomHelper::getNewsCommentCount($blog->id)}}</p>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php }}?>



                                </div>
                            </div> 
                        </div>

                        <?php $j++;}}?>

                    </div>
                </div>
            </div>
        </section> 
        <!-- *************************** Get Strated ************************************* -->
            <!-- <section class="">
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
        </section> -->


        <!-- ************************** Footer Section ******************************** -->

        <script type="text/javascript">
            $( document ).ready(function() {
                var type_value = '{{$type_value}}';
                var key_value = '{{$key_value}}';
                save_tab_keys(type_value,key_value);
            });
        </script>
        @endsection