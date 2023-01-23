@extends('front.common.layout')

@section('content')

<?php 
$exam = Session::get('exam');
$subcategories = CustomHelper::getSubCategory($exam);
// pr($subcategories);
$tab_keys_course = Session::get('tab_keys_course');

$type_value = $tab_keys_course['type']??'course';
if(empty($type) && $type_name == ''){
    $key_value = $tab_keys_course['key']??'';
    if(empty($key_value)){
        $key_value = $subcategories[0]->slug??'';
    }
}else{
    $key_value = $type??'';
}

$type_id = $types->id??'';
if($type_name == 'modules'){
    $key_value = $subcategories[0]->slug??'';
}
// pr($type_name);
?>
<section class="py-5 bg-light sec-marign">

    <div class="container"> 
               <!--  <select class="form-select w-25 mb-4" aria-label="Default select example"> 
                    <option value="1" selected>Banking</option>
                    <option value="2">SSC</option>
                    <option value="3">Railway</option>
                </select> -->

                <div class="row"> 
                    <div class="col-12">
                        <ul class="nav nav-pills  flex-nowrap mb-3 overflow-scroll coursescroll" id="pills-tab" role="tablist">
                            <?php if(!empty($subcategories)){
                                $i = 0;
                                foreach($subcategories as $sub_cat){
                                 $active = '';
                                 if($type_value =='course' && $sub_cat->slug == $key_value){
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
                                    <button class="nav-link text-nowrap rounded {{$active}} border me-md-3 " id="pills-ibps-tab{{$sub_cat->id}}" data-bs-toggle="pill" data-bs-target="#pills-ibps{{$sub_cat->id}}" type="button" role="tab" aria-controls="pills-ibps" aria-selected="true" onclick="save_tab_keys('course','{{$sub_cat->slug}}')">{{$sub_cat->name??''}}</button>
                                </li>
                                <?php $i++;}}?>

                            </ul>
                        </div>

                        <div class="tab-content" id="pills-tabContent">
                            <!--  ibps Us section   -->
                            <?php if(!empty($subcategories)){
                                $j = 0;
                                foreach($subcategories as $sub_cat){
                                   $active = '';
                                   if($type_value =='course' && $sub_cat->slug == $key_value){
                                     $active = "active";
                                 }else{
                                    if($i == 0 && $active ==''){
                                    // $active = "active";
                                    }else{
                                        $active = '';
                                    }
                                }

                                $courses = CustomHelper::getCourses($exam,$sub_cat->id,$type_id,'');
                                ?>
                                <div class="tab-pane fade show {{$active}}" id="pills-ibps{{$sub_cat->id}}" role="tabpanel" aria-labelledby="pills-ibps-tab{{$sub_cat->id}}" tabindex="0"> 
                                    <div class="container"> 
                                        <div class="row g-3 ">

                                            <?php if(!empty($courses)){
                                                foreach($courses as $course){
                                                    ?>
                                                    <div class="col-12 col-lg-6">
                                                        <a href="{{route('course_details',['slug'=>$course->slug])}}" class="d-block free-quiz-box bg-white shadow-sm p-3 rounded">
                                                            <div class="d-flex align-items-center ">
                                                                <div class="">
                                                                    <img src="{{CustomHelper::getImageUrl('course',$course->image)}}" alt="blogimg" class="img-fluid rounded" width="150">
                                                                </div>
                                                                <div class="col-9 ms-3">
                                                                 <div class="d-flex align-items-center justify-content-between  overflow-scroll mb-2 coursescroll">
                                                                    <?php 
                                                                    $types = DB::table('course_types')->where('course_id', $course->id)->get();
                                                                    if (!empty($types)) {
                                                                        foreach ($types as $type) {
                                                                            $type = \App\Models\Types::where('id', $type->type_id)->first();
                                                                            if (!empty($type)) {?>
                                                                                <span class="bg-light px-2 py-1 rounded text-nowrap mx-2">{{$type->name??''}}</span>
                                                                            <?php }}}?>

                                                                            <span class="text-nowrap" ><i class="bi bi-star-fill text-warning "></i>4.5</span>
                                                                        </div>
                                                                        <h4 class="free-quiz-heading fs-19">{{$course->name??''}}</h4>
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <span class="text-muted"><del>₹{{$course->mrp??''}}</del></span>
                                                                            <span class="ms-3 t-green">₹{{$course->price??''}} <span class="fs-10">50%off</span></span>
                                                                            <button class="btn px-3 text-white green" tabindex="-1">Buy</button>
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

                    <script type="text/javascript">
                        $( document ).ready(function() {
                            var type_value = '{{$type_value}}';
                            var key_value = '{{$key_value}}';
                            save_tab_keys(type_value,key_value);
                        });
                    </script>
                    @endsection