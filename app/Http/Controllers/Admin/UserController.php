<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Auth;
use Validator;
use App\Models\Admin;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\SubCategory;
use App\Models\Banner;
use App\Models\State;
use App\Models\Role;
use App\Models\City;
use App\Models\User;
use App\Models\SubscriptionHistory;
use App\Models\Transaction;
use App\Roles;
use App\Models\ExamCategory;
use Storage;
use DB;
use Hash;


Class UserController extends Controller
{

    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        $search = isset($request->search) ? $request->search :'';




        $data['search'] = $search;
        $users = User::where('is_delete','0')->orderBy('id','desc');
        if(!empty($search)){
            $users->where('name', 'like', '%' . $search . '%');
            $users->orWhere('email', 'like', '%' . $search . '%');
            $users->orWhere('phone', 'like', '%' . $search . '%');
        }
        $users = $users->paginate(10);
        $data['users'] = $users;
        return view('admin.user.index',$data);
    }



    public function export(Request $request){
        $search = isset($request->search) ? $request->search:'';
        $users = User::select('id','name','email','phone','wallet','referral_code');
        if(!empty($search)){
            $users->where('name', 'like', '%' . $search . '%');
            $users->orWhere('email', 'like', '%' . $search . '%');
            $users->orWhere('phone', 'like', '%' . $search . '%');

        }
        $users = $users->get();
        if(!empty($users) && $users->count() > 0){
            foreach($users as $user){
             $userArr = [];
             $userArr['ID'] = $user->id;
             $userArr['Name'] = $user->name ?? '';
             $userArr['Email'] = $user->email ?? '';
             $userArr['Phone'] = $user->phone ?? '';
             $userArr['Wallet'] = $user->wallet ?? 0;
             $userArr['Referal Code'] = $user->referral_code ?? 0;
             $exportArr[] = $userArr;
         }
         $filedNames = array_keys($exportArr[0]);
         $fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';
         return Excel::download(new UserExport($exportArr, $filedNames), $fileName);
     }

 }







 public function subscriptions(Request $request){
     $id = isset($request->id) ? $request->id : 0;
     $data = [];
     $method = $request->method();


     if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['course_id'] = 'required';

        $this->validate($request,$rules);

        $dbArray = [];


        $course_details = Course::where('id',$request->course_id)->first();

        $dbArray['course_id'] = $request->course_id;
        $dbArray['user_id'] = $id;
        $dbArray['start_date'] = date('Y-m-d');
        $dbArray['amount'] = $course_details->full_amount;
        $dbArray['payment_type'] = 'Admin';
        $dbArray['payment_cause'] = 'subscription';
        $dbArray['paid_status'] = 1;
        $dbArray['end_date'] = date('Y-m-d',strtotime("+".$course_details->duration." months", strtotime(date('Y-m-d'))));

        SubscriptionHistory::insert($dbArray);
    }

    $subscriptions = SubscriptionHistory::where('is_delete',0)->where('user_id',$id)->latest()->paginate(10);

    $back_url = $this->ADMIN_ROUTE_NAME.'/user';


    $course = Course::select('id','course_name')->where('status',1)->get();


    $data['user'] = User::where('id',$id)->first();
    $data['subscriptions'] = $subscriptions;
    $data['course'] = $course;

    $data['back_url'] = $back_url;




    return view('admin.user.subscription',$data);


}


public function update_subscription(Request $request){
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        SubscriptionHistory::where('id',$request->subscription_id)->update(['end_date'=>$request->end_date]);
        return back()->with('alert-success', 'Updated Successfully');

    }
}





public function add(Request $request)
{
 $details = [];

 $id = isset($request->id) ? $request->id : 0;

 $users = '';

 if(is_numeric($id) && $id > 0)
 {
    $users = User::find($id);
    if(empty($users))
    {
        return redirect($this->ADMIN_ROUTE_NAME.'/user');
    }
}


if($request->method() == "POST" || $request->method() == "post")
{

           // prd($request->toArray());

    if(empty($back_url))
    {
     $back_url = $this->ADMIN_ROUTE_NAME.'/user';
 }


 if(is_numeric($request->id) && $request->id > 0)
 {
     $details['name'] = 'required';
     $details['email'] = 'required';
     $details['mobile_number'] = 'required';
     $details['status'] = 'required';


 }else{

    $details['name'] = 'required';
    $details['email'] = 'required';
    $details['mobile_number'] = 'required';
    $details['status'] = 'required';

}

$this->validate($request , $details); 

          // prd($dd);

$createdDetails = $this->save($request , $id);

if($createdDetails)
{
    $alert_msg = "User Created Successfully";

    if(is_numeric($id) & $id > 0)
    {
        $alert_msg = "User Updated Successfully";
    } 
    return redirect(url($back_url))->with('alert-success',$alert_msg);
}else{

    return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
}

}

$page_Heading = "Add User";
if(isset($users->id))
{
    $name = $users->name;
    $page_Heading = 'Update User -'.$name;
}



$details['page_Heading'] = $page_Heading;
$details['id'] = $id;
$details['users'] = $users;


return view('admin.user.form',$details);

}


public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url']);

    if(!empty($request->password)){
        $details['password'] = bcrypt($request->password);
    }

    $old_img = '';

    $user = new User;

    if(is_numeric($id) && $id > 0)
    {
        $exist = User::find($id);

        if(isset($exist->id) && $exist->id == $id)
        {   
            $user = $exist;

            $old_img = $exist->image;

        }

    }

    foreach($details as $key => $val)
    {
        $user->$key = $val;
    }

    $isSaved = $user->save();

    if($isSaved)
    {
        $this->saveImage($request , $user , $old_img);
    }

    return $isSaved;
}

private function saveImage($request, $user, $oldImg=''){

    $file = $request->file('image');

    //prd($file);
    if ($file) {
        $path = 'user/';
        $thumb_path = 'user/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

            // prd($uploaded_data);
        if($uploaded_data['success']){

            if(!empty($oldImg)){
                if($storage->exists($path.$oldImg)){
                    $storage->delete($path.$oldImg);
                }
                if($storage->exists($thumb_path.$oldImg)){
                    $storage->delete($thumb_path.$oldImg);
                }
            }
            $image = $uploaded_data['file_name'];

           // prd($image);
            $user->image = $image;
            $user->save();         
        }

        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}

public function change_users_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $faculties = User::where('id',$id)->first();
  if(!empty($faculties)){

   User::where('id',$id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}

public function delete(Request $request)
{
 $id = isset($request->id) ? $request->id : 0;
 $is_delete = 0;

 if(is_numeric($id) && $id > 0)
 {       
    $is_delete = User::where('id', $id)->update(['is_delete'=> '1']);
}

if(!empty($is_delete))
{
    return back()->with('alert-success', 'User Deleted Successfully');
}else{

    return back()->with('alert-danger', 'something went wrong, please try again...');
}    
}
///////////////////////////////////////////////Profile//////////////////////////////////









public function profile(Request $request){
    $id = isset($request->id) ? $request->id :'';

    $users = User::where('id',$id)->first();


    $data['users'] = $users;

    $transactions = Transaction::where('user_id',$id)->latest()->get();

    $data['transactions'] = $transactions;
    


    $data['subscription_history'] = [];


    return view('admin.user.profile',$data);
}





public function wallet_update(Request $request){
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){

        $rules = [
           'user_id' => 'required',
           'amount' => 'required',
           'type' => 'required',
           'remarks' => 'required',
       ];

       $validator =  Validator::make($request->all(), $rules);
       if($validator->fails()){
        $messages = $validator->messages();

        foreach ($rules as $key => $value)
        {
            $verrors[] = $messages->first($key);
        }
        return json_encode(['status'=>false,'message'=>$verrors]);
    }else{
        $message = 'Wallet Updated Successfully';
        $user = User::where('id',$request->user_id)->first();
        $new_wallet = $user->wallet;
        if($request->type == 'credit'){
            $new_wallet = (int)$user->wallet + (int)$request->amount;
        }
        if($request->type == 'debit'){
            if($user->wallet < $request->amount){
                $message = 'Insufficient Balance';
                return json_encode(['status'=>true,'message'=>$message]);
            }
            $new_wallet = (int)$user->wallet - (int)$request->amount;
            if($new_wallet < 0){
                $new_wallet = 0;
            }
        }
        $user->wallet = $new_wallet;
        $user->save();

        $dbArray = [];
        $dbArray['user_id'] = $user->id;
        $dbArray['txn_no'] = 'AMBABA'.$user->id.'AB'.rand(11111,9999999);
        $dbArray['reason'] = $request->remarks;
        $dbArray['type'] = $request->type;
        $dbArray['amount'] = $request->amount;
        $dbArray['status'] = 1;
        $dbArray['created_at'] = date('Y-m-d H:i:s');
        Transaction::insert($dbArray);

        return json_encode(['status'=>true,'message'=>$message,'amount'=>$new_wallet]);

    }
}

}




public function update_profile(Request $request){
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $rules = [
           'user_id' => 'required',
       ];

       $validator =  Validator::make($request->all(), $rules);
       if($validator->fails()){
        $messages = $validator->messages();
        foreach ($rules as $key => $value)
        {
            $verrors[] = $messages->first($key);
        }
        return json_encode(['status'=>false,'message'=>$verrors]);
    }else{
        $message = 'Profile Updated Successfully';
        $user = User::where('id',$request->user_id)->first();

        if(!empty($request->name)){
            $user->name = $request->name;
        }
        if(!empty($request->email)){
            $user->email = $request->email;
        }
        if(!empty($request->mobile_number)){
            $user->mobile_number = $request->mobile_number;
        }
        if(!empty($request->dob)){
            $user->dob = $request->dob;
        }
        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_name = $this->saveUserImage($request);
            $user->profile_picture = $file_name;
        }

        $user->save();

        return json_encode(['status'=>true,'message'=>$message]);

    }
}

}




private function saveUserImage($request){

    $file = $request->file('image');
    if ($file) {
        $path = 'user/';
        $thumb_path = 'user/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

            // prd($uploaded_data);
        if($uploaded_data['success']){

            if(!empty($oldImg)){
                if($storage->exists($path.$oldImg)){
                    $storage->delete($path.$oldImg);
                }
                if($storage->exists($thumb_path.$oldImg)){
                    $storage->delete($thumb_path.$oldImg);
                }
            }
            $image = $uploaded_data['file_name'];

            return $image;       
        }
    }else{
     return '';
 }

}


public function free_subscription(Request $request){
  $method = $request->method();
  if($method == 'POST' || $method == 'post'){
    $rules = [
       'user_id' => 'required',
       'course_id' => 'required',
   ];

   $validator =  Validator::make($request->all(), $rules);
   if($validator->fails()){
    $messages = $validator->messages();
    foreach ($rules as $key => $value)
    {
        $verrors[] = $messages->first($key);
    }
    return json_encode(['status'=>false,'message'=>$verrors]);
}else{
    $course = Course::where('id',$request->course_id)->first();
    $subscriptionArr = [];
    $subscriptionArr['user_id'] = $request->user_id;
    $subscriptionArr['type_id'] = $request->course_id;
    $subscriptionArr['address_id'] = 0;
    $subscriptionArr['start_date'] = date('Y-m-d');
    $end_date = '';
    if($course->type == 'day'){
        $end_date = date('Y-m-d', strtotime("+".$course->duration." day", strtotime(date('Y-m-d'))));
    }
    if($course->type == 'month'){
        $end_date = date('Y-m-d', strtotime("+".$course->duration." month", strtotime(date('Y-m-d'))));
    }
    if($course->type == 'year'){
        $end_date = date('Y-m-d', strtotime("+".$course->duration." year", strtotime(date('Y-m-d'))));
    }
    $txn_no = 'AMBABA'.rand(111111,999999999);;
    $subscriptionArr['end_date'] = $end_date;
    $subscriptionArr['type'] = 'course';
    $subscriptionArr['amount'] = $course->price;
    $subscriptionArr['payment_type'] = 'admin';
    $subscriptionArr['coupon_code'] = '';
    $subscriptionArr['payment_cause'] = 'Subscription Purchase From Admin';
    $subscriptionArr['paid_status'] = 1;
    $subscriptionArr['gateway'] = 'admin';
    $subscriptionArr['online_amount'] = '0';
    $subscriptionArr['wallet_amount'] = '0';
    $subscriptionArr['discount'] = '0';
    $subscriptionArr['txn_no'] = $txn_no;
    $subscriptionArr['is_delete'] = '0';

    SubscriptionHistory::insert($subscriptionArr);


    $transactionArr = [];
    $transactionArr['user_id'] = $request->user_id;
    $transactionArr['txn_no'] = $txn_no;
    $transactionArr['reason'] = 'Subscription Purchase From Admin';
    $transactionArr['type'] = 'debit';
    $transactionArr['amount'] = 0;
    $transactionArr['status'] = '1';

    Transaction::insert($transactionArr);


    return json_encode(['status'=>true,'message'=>'Subscribed Successfully']);

}
}
}


public function update_subs_enddate(Request $request){
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){
        $rules = [
           'subscription_id' => 'required',
           'end_date' => 'required',
       ];
       $this->validate($request,$rules);

       SubscriptionHistory::where('id',$request->subscription_id)->update(['end_date'=>$request->end_date]);
    return back()->with('alert-success', 'Subscription Updated Successfully');

   }





}





















}




