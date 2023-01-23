<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Admin;
use Image;


use App\Models\Category;
use App\Models\City;
use App\Models\CourseType;
use App\Models\SubCategory;
use App\Models\Achievers;
use App\Models\SubscriptionHistory;

use App\Models\Course;
use App\Models\Business;
use App\Models\Permission;
use App\Models\BusinessCategory;
use App\Models\Roles;
use App\Models\State;
use App\Models\Banner;
use App\Models\Types;
use App\Models\News;
use App\Models\Exam;

use Storage;
use DB;
use Hash;
use FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;

use PhpOffice\PhpWord\IOFactory;
use Session;




Class HomeController extends Controller
{



   public  function generateReferalCode($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $exist = User::where('referal_code', $randomString)->first();
    if (!empty($exist)) {
        self::generateReferalCode($length);
    }
    return $randomString;
}


public function index(Request $request){
        // $users = User::get();
        // foreach($users as $user){
        //     $referal_code = strtoupper($this->generateReferalCode());
        //     User::where('id',$user->id)->update(['referal_code'=>$referal_code]);
        // }


  $data = [];
  $exam_category = Session::get('exam')??1;
  $banners = Banner::where('status', 1)->where('category_id', $exam_category)->where('is_delete', 0)->get();
  $course_by_exam = SubCategory::where('status', 1)->where('is_delete', 0)->where('category_id', $exam_category)->limit(8)->get();

        // prd($course_by_exam);
  $course_by_type = Types::where('status', 1)->where('is_delete', 0)->get();
  $top_courses = Course::where('status', 1)->where('is_delete', 0)->where('category_id', $exam_category)->get();
  $latest_posts = News::where('status', 1)->where('type', 'posts')->latest()->limit(5)->get();
  $latest_blogs = News::where('status', 1)->where('type', 'blogs')->limit(4)->get();
  $daily_quiz = Exam::select('id', 'title', 'no_of_questions', 'time_per_question')->where('status', 1)->where('type', 'quiz')->latest()->limit(5)->get();

  $our_achievers = Achievers::where('status', 1)->latest()->limit(5)->get();


  $trusted_students = 1;
  $selections_students = 12;
  $success_rate = 15;
  $total_test_attempt = 16;

  $trusted = [
    'trusted_students'=>$trusted_students,
    'selections_students'=>$selections_students,
    'success_rate'=>$success_rate,
    'total_test_attempt'=>$total_test_attempt,
];



$data['banners'] = $banners;
$data['top_courses'] = $top_courses;
$data['course_by_type'] = $course_by_type;
$data['course_by_exam'] = $course_by_exam;
$data['latest_posts'] = $latest_posts;
$data['latest_blogs'] = $latest_blogs;
$data['daily_quiz'] = $daily_quiz;
$data['our_achievers'] = $our_achievers;
$data['trusted'] = $trusted;
return view('front.index',$data);
}

public function courses(Request $request){
   Session::forget('tab_keys_course');
   $data = [];  
   $type = $request->type??'';
   $type_name = $request->type_name??'';
   $types = [];
   $course_ids = [];
   if($type_name == 'modules' && !empty($type)){
    $types = Types::where('slug',$type)->first();
}
if($type_name == 'subcategory' && !empty($type)){
    $types = [];
}
$data['types'] = $types;
$data['type'] = $type;
$data['type_name'] = $type_name;


return view('front.courses',$data);
}

public function about(Request $request){
    $data = [];      

    return view('front.about_us',$data);
}

public function contact(Request $request){
    $data = [];      

    return view('front.contact',$data);
}

public function course_details(Request $request){
    $data = []; 

    $slug = $request->slug??'';
    $course = Course::where('slug',$slug)->first();

    $data['course'] = $course;     

    return view('front.course_details',$data);
}


public function blogs(Request $request){
    $data = []; 
    Session::forget('tab_keys');

    return view('front.blogs',$data);
}

public function current_affairs(Request $request){
    $data = [];
    $array = ['type'=>'news','key'=>'current_affairs'];
    Session::put('tab_keys',$array);
    return view('front.blogs',$data);
}



public function update_exam_category(Request $request){
    $exam = $request->exam??'';
    Session::put('exam', $exam);
    if(!empty(Auth::guard('appusers')->user())){
        $user_id = Auth::guard('appusers')->user()->id??'';
        User::where('id',$user_id)->update(['exam'=>$exam]);
    }
    return json_encode(['status'=>true]);

}




public function save_tab_keys(Request $request){
    $type = $request->type??'';
    $key = $request->key??'';
    $array = ['type'=>$type,'key'=>$key];
    if($type == 'news'){
        Session::put('tab_keys',$array);
    }if($type == 'course'){
        Session::put('tab_keys_course',$array);
    }
    echo 1;
}












public function send_otp(Request $request){
    $mobile_number = $request->mobile??'';
    if(!empty($mobile)){
        $user = User::where(['mobile_number' => $mobile_number])->first();
        if (empty($user)) {
            $user = User::create([
                'mobile_number' => $mobile_number,
                'password' => bcrypt($mobile_number),
                'auth_medium' => 1
            ]);
        }
        // $otp = rand(1111, 9999);
        $otp = 1234;
        $user->otp = $otp;
        $user->otp_expiration = Carbon::now()->addMinutes(10);
        $user->save();
        $this->send_sms($mobile_number, $otp);
    }

    return response()->json([
            'success' => true,
            'message' => 'OTP Sent',
            'is_new' => empty($user->name)
        ], 200); 

}


}




