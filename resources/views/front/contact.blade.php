@extends('front.common.layout')

@section('content')


 <!--**************************** Hero page Style *******************  -->

 <div class="contacthero d-flex align-items-center">
    <div class="container">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-lg-6">
                <div class="welcome-intro"> 
                    <h1 class="mb-3  ">Contact Us</h1> 
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="welcome-intro-img text-end d-none d-lg-block">
                    <img src="{{asset('public/web_assets/image/contact-us-banner.png')}}" alt="herostudentimg" class="img-fluid" width="50%">
                </div>
            </div>
        </div>
    </div>
</div>
     

<!-- ***************************Select Exame Modal***************************  --> 
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content position-relative">
        <div class="modal-header border-0">
          <div>
            <h1 class="modal-title fs-5" id="exampleModalLabel">Select Exame</h1>
            <p>Choose an exam your are preparing</p>
          </div>
          <button type="button" class="btn-close cus-modal " data-bs-dismiss="modal" aria-label="Close" >
        <img src="{{asset('public/web_assets/image/modal_cross.svg')}}" alt="">
        </button>
        </div>
        <div class="modal-body">
           <div class="row g-3">
            <div class="col-12">
                <div class="px-2">
                    <a href class="row justify-content-between align-items-center border bg-white shadow-sm" >
                        <div class="col-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="course-box">
                                    <img src="{{asset('public/web_assets/image/jaiib-1649001786.png')}}" alt="">
                                </div>
                                <p class="ms-3 mb-0">JAIIB</p>
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="d-flex justify-content-end align-items-center">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-12">
                <div class="px-2">
                    <a href class="row justify-content-between align-items-center border bg-white shadow-sm" >
                        <div class="col-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="course-box">
                                    <img src="{{asset('public/web_assets/image/jaiib-1649001786.png')}}" alt="">
                                </div>
                                <p class="ms-3 mb-0">JAIIB</p>
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="d-flex justify-content-end align-items-center">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-12">
                <div class="px-2">
                    <a href class="row justify-content-between align-items-center border bg-white shadow-sm" >
                        <div class="col-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="course-box">
                                    <img src="{{asset('public/web_assets/image/jaiib-1649001786.png')}}" alt="">
                                </div>
                                <p class="ms-3 mb-0">JAIIB</p>
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="d-flex justify-content-end align-items-center">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-12">
                <div class="px-2">
                    <a href class="row justify-content-between align-items-center border bg-white shadow-sm" >
                        <div class="col-2 p-2">
                            <div class="d-flex align-items-center">
                                <div class="course-box">
                                    <img src="{{asset('public/web_assets/image/jaiib-1649001786.png')}}" alt="">
                                </div>
                                <p class="ms-3 mb-0">JAIIB</p>
                            </div>
                        </div>
                        <div class="col-10">
                            <div class="d-flex justify-content-end align-items-center">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            
           </div>
        </div> 
      </div>
    </div>
  </div> 
  <!-- Modal End-->
  <!--************************** Quize Listing********************** -->
  <section class="py-5 bg-light">
    <div class="container">
        <div class="row align-itmes-center justify-content-center">
            <div class="col-12 col-md-6 ">
                <div class="bg-white p-3 shadow-sm">
                    <h1 class="m-0 fw-bold contact-us-heading">Have Question? Write a Message</h1>
                    <p class="mt-3 mb-4 fs-4">We will catch you as early as we receive the message</p>
                    <form class="row g-3">
                        <div class="col-md-6 b">
                            <div class="border">
                            <input type="text" class="form-control bg-white " id="InputName" placeholder=" Full Name*" required="" aria-required="true">
                           </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="border"> 
                            <input type="text" class="form-control bg-white" id="InputNumber" placeholder="Mobile Number" aria-required="true">
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border"> 
                            <input type="email" class="form-control bg-white" id="inputEmail4" placeholder="Email Address*" aria-required="true" required="">
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border"> 
                            <input type="text" class="form-control bg-white" id="subject" placeholder="Subject of Contact*" aria-required="true">
                        </div>
                        </div>
                        <div class="col-md-12">
                            <div class="border">
                            <textarea class="form-control t-area bg-white" row="4" aria-label="With textarea" placeholder="Message*"></textarea>
                        </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn cus-btn text-white bg-theme rounded">Send Now</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="p-3 d-flex align-itmes-center mb-4 shadow-sm" style="background:#ffecec;">
                    <div>
                    <span class="fs-5"> For Sales:</span>
                    <div class="info-body mt-2">
                        <div class=" ">
                            <span class="me-2">
                                <i class="bi bi-telephone-fill fs-14"></i>
                            </span>
                            <a href="tel:+91-7321961084" class="fs-13"> +91 1254687912</a>
                        </div>
                        <div class=" ">
                            <span class="me-2">
                                <i class="bi bi-envelope-fill fs-14"></i>
                            </span> 
                            <a href="mailto:info@teknikoglobal.com" class="fs-13">suport@mail.com</a>
                        </div> 
                    </div>
                    </div>
                </div>
                <div class="p-3 d-flex align-itmes-center mb-4 shadow-sm" style="background:#ff9f86;">
                    <div>
                    <span class="fs-5">For Managers:</span>
                    <div class="info-body mt-2">
                        <div class=" ">
                            <span class="me-2">
                                <i class="bi bi-telephone-fill fs-14"></i>
                            </span>
                            <a href="tel:+91-7321961084" class="fs-13"> +91 1254687912</a>
                        </div>
                        <div class=" ">
                            <span class="me-2">
                                <i class="bi bi-envelope-fill fs-14"></i>
                            </span> 
                            <a href="mailto:info@teknikoglobal.com" class="fs-13">suport@mail.com</a>
                        </div> 
                    </div>
                    </div>
                </div>

                <div class="p-3 d-flex align-itmes-center shadow-sm" style="background:#eafffd;">
                    <div>
                    <span class="fs-5"> For HR:</span>
                    <div class="info-body mt-2">
                        <div class=" ">
                            <span class="me-2">
                                <i class="bi bi-telephone-fill fs-14"></i>
                            </span>
                            <a href="tel:+91-7321961084" class="fs-13"> +91 1254687912</a>
                        </div>
                        <div class=" ">
                            <span class="me-2">
                                <i class="bi bi-envelope-fill fs-14"></i>
                            </span> 
                            <a href="mailto:info@teknikoglobal.com" class="fs-13">suport@mail.com</a>
                        </div> 
                    </div>
                    </div>
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
                    <div class="get-started d-flex justify-content-md-between justify-content-center align-items-center flex-column flex-md-row p-md-5">
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