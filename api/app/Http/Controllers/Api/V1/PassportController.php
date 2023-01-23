<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\UserLogin;
use App\Models\ExamCategory;
use App\Models\Faqs;
use App\Models\Banner;
use App\Models\SubCategory;
use App\Models\Types;
use App\Models\LiveClass;
use App\Models\Course;
use App\Models\Rating;
use App\Models\News;
use App\Models\Exam;
use App\Models\Achievers;
use App\Models\UserDocument;
use App\Models\Transaction;
use App\Models\CourseType;
use App\Models\BankDetails;
use App\Helpers\CustomHelper;
use App\Models\LoanDetails;
use DB;

class PassportController extends Controller
{

    public function __construct()
    {
        $this->user = new User;
        date_default_timezone_set("Asia/Kolkata");
        $this->url = env('BASE_URL');
    }

    public function send_otp(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'mobile_number' => 'required|digits:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'is_new' => false

            ]);
        }

        $mobile_number = $request->input('mobile_number');
        $user = User::where(['mobile_number' => $mobile_number])->first();
        if (empty($user)) {
            $user = User::create([
                'mobile_number' => $mobile_number,
                'password' => bcrypt($mobile_number),
                'auth_medium' => 1
            ]);
        }
        if($mobile_number == '7065452862' || $mobile_number == '6370371406'){
            $otp = 1234;
        }else{
            $otp = rand(1111, 9999);
        }
        $user->otp = $otp;
        $user->otp_expiration = Carbon::now()->addMinutes(10);
        $user->save();
        // $this->send_sms($mobile_number, $otp);
        return response()->json([
            'success' => true,
            'message' => 'OTP Sent',
            'is_new' => empty($user->name)
        ], 200);
    }


    public function verify_otp(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'mobile_number' => 'required|digits:10',
            'otp' => 'required|numeric'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'is_new' => false,
                'token' => null,
                'user' => null,

            ]);
        }

        $mobile_number = $request->input('mobile_number');
        $otp = $request->input('otp');

        $user = User::where(['mobile_number' => $mobile_number, 'otp' => $otp])->first();
        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
                'is_new' => empty($user->name),
                'token' => null,
                'user' => null,

            ], 200);
        }
        $otp = rand(1111, 9999);
        // $otp = 9999;
        $user->otp = null;
        $user->otp_expiration = null;
        $user->save();
        if (!empty($user->name)) {
            $input = ['mobile_number' => $user->mobile_number, 'password' => $user->mobile_number];
            if (Auth::attempt($input)) {
                $token = auth()->user()->createToken('ambitiousbaba')->accessToken;

                $user->gender = $user->gender ?? '';
                $user->email_verified_at = $user->email_verified_at ?? '';
                $user->otp = $user->otp ?? '';
                $user->otp_expiration = $user->otp_expiration ?? '';
                $user->dob = $user->dob ?? '';
                $user->profile_picture = $this->getImageUrl('user', $user->profile_picture);
                return response()->json([
                    'success' => true,
                    'is_new' => false,
                    'message' => 'User login succesfully, Use token to authenticate. Verify OTP',
                    'token' => $token,
                    'user' => $user,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'is_new' => true,
                    'message' => 'User authentication failed.',
                    'token' => null,
                    'user' => null,
                ], 401);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP Verified Successfully',
            'is_new' => empty($user->name),
            'token' => null,
            'user' => null,

        ], 200);
    }
    public function profile(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            // 'token' => 'required',
        ]);
        $user = null;
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
                'user' => $user,
            ], 400);
        }
        $user = auth()->user();
        if (empty($user)) {
            return response()->json([
                'result' => false,
                'message' => '',
                'user' => $user,
            ], 401);
        }
        $user->gender = $user->gender ?? '';
        $user->email_verified_at = $user->email_verified_at ?? '';
        $user->otp = $user->otp ?? '';
        $user->otp_expiration = $user->otp_expiration ?? '';
        $user->dob = $user->dob ?? '';
        $user->profile_picture = $this->getImageUrl('user', $user->profile_picture);
        return response()->json([
            'result' => true,
            'message' => 'User Profile',
            'user' => $user,
        ], 200);
    }

    public function types(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'course_id' => 'required',
        ]);
        $types = null;
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
                'types' => $types,
            ], 400);
        }
        $user = auth()->user();
        if (empty($user)) {
            return response()->json([
                'result' => false,
                'message' => '',
                'types' => $types,
            ], 401);
        }
        $tags = [];
        $types = DB::table('course_types')->where('course_id', $request->course_id)->get();

        // $tagArr = [];
        // $tagArr['name'] = 'All';
        // $tagArr['slug'] = 'all';
        // $tagArr['count'] = '1';
        // $tagArr['icon'] = "";
        // $tags[] = $tagArr;


        if (!empty($types)) {
            foreach ($types as $type) {
                $type = Types::where('id', $type->type_id)->first();
                if (!empty($type)) {
                    $tagArr = [];
                    $tagArr['name'] = $type->name ?? '';
                    $tagArr['slug'] = $type->slug ?? '';
                    $tagArr['count'] = '1';
                    $tagArr['icon'] = $this->getImageUrl('types',$type->icon);
                    $tags[] = $tagArr;
                }
            }
        }
        
        return response()->json([
            'result' => true,
            'message' => 'Types List',
            'types' => $tags,
        ], 200);
    }



    public function register(Request $request)
    {

        // $input = $request->only(['name', 'email', 'mobile_number', 'city']);
        $validate_data = [
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'mobile_number' => 'required|digits:10',
            'deviceID' => '',
            'deviceToken' => '',
            'deviceType' => '',
        ];

        $validator = Validator::make($request->all(), $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'token' => null,
                'user' => null,
            ]);
        }
        $mobile_number = $request->input('mobile_number');
        $user = User::where(['mobile_number' => $mobile_number])->first();
        if (!empty($user)) {
            $user->name = $request->name ?? '';
            $user->income_type = $request->income_type ?? '';
            $user->pan_card = $request->pancard ?? '';
            $user->email = $request->email ?? '';
            $user->address = $request->address ?? '';
            $user->state = $request->state ?? '';
            $user->pincode = $request->pincode ?? '';
            $user->residence_type = $request->residence_type ?? '';
            $user->alternate_no = $request->alternate_no ?? '';
            $user->referene_contact = $request->referene_contact ?? '';
            $user->residing_with_family = $request->residing_with_family ?? '';
            $user->marriage_status = $request->marriage_status ?? '';
            $user->education = $request->education ?? '';
            $user->gender = $request->gender ?? '';
            $user->referal_code = $this->generateReferalCode() ?? '';
            $user->password = bcrypt($mobile_number);
            $user->save();
            $deviceID = $request->input("deviceID");
            $deviceToken = $request->input("deviceToken");
            $deviceType = $request->input("deviceType");
            $device_info = UserLogin::where(['user_id' => $user->id])->first();
            UserLogin::updateOrcreate([
                'user_id' => $user->id
            ], [
                'deviceToken' => $deviceToken,
                'deviceID' => $deviceID,
                'deviceType' => $deviceType,
            ]);


            $input = ['mobile_number' => $request->mobile_number, 'password' => $request->mobile_number];
            // $usersauth = Auth::attempt(['email'=>$request->email,'password'=>$request->mobile_number]);

            if (Auth::attempt($input)) {
                $token = auth()->user()->createToken('advancesalary')->accessToken;
                $user->gender = $user->gender ?? '';
                $user->email_verified_at = $user->email_verified_at ?? '';
                $user->otp = $user->otp ?? '';
                $user->otp_expiration = $user->otp_expiration ?? '';
                $user->dob = $user->dob ?? '';
                return response()->json([
                    'success' => true,
                    'message' => 'User login succesfully, Use token to authenticate Register.',
                    'token' => $token,
                    'user' => $user,
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User authentication failed.',
                    'token' => null,
                    'user' => null,
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'OTP is Not Verified',
                'token' => null,
                'user' => null,
            ], 200);
        }
    }




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


    public function login(Request $request)
    {


        $validate_data = [
            'mobile_number' => 'required',
        ];

        $validator = Validator::make($request->all(), $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'token' => null,
                'user' => null,
            ]);
        }

        $mobile_number = $request->mobile_number ?? '';
        $user = User::where(['mobile_number' => $mobile_number])->first();
        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found',
                'token' => null,
                'user' => null,
            ], 200);
        }
        $input = ['mobile_number' => $user->mobile_number, 'password' => $user->mobile_number];

        if (auth()->attempt($input)) {
            $token = auth()->user()->createToken('passport_token')->accessToken;
            $user->gender = $user->gender ?? '';
            $user->email_verified_at = $user->email_verified_at ?? '';
            $user->otp = $user->otp ?? '';
            $user->otp_expiration = $user->otp_expiration ?? '';
            $user->dob = $user->dob ?? '';
            return response()->json([
                'success' => true,
                'message' => 'User login succesfully, Use token to authenticate.',
                'token' => $token,
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.',
                'token' => null,
                'user' => null,
            ], 401);
        }
    }


    public function logout()
    {
        $access_token = auth()->user()->token();
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($access_token->id);
        return response()->json([
            'result' => true,
            'message' => 'User logout successfully.'
        ], 200);
    }


    public function upload_image($user_id, $file, $path, $is_thumb = false)
    {
        $side_name = $user_id . '_user_' . time() . rand(1111, 99999999) . '.' . $file->getClientOriginalExtension();
        $file->move($path, $side_name);
        $path1 = $path . 'thumb/';
        if ($is_thumb == true) {
            //copy($path.$side_name,$path1.$side_name);

        }
        return $side_name;
    }


    public function getImageUrl($path, $filename)
    {
        if (!empty($filename)) {
            // $filename = str_replace(' ', '', $filename);
            return $this->url . "/public/storage/" . $path . '/' . $filename;
        } else {
            return '';
        }
    }

    public function update_profile(Request $request)
    {
        $validator =  Validator::make($request->all(), []);
        $user = null;
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
                'user_details' => $user,
            ], 400);
        }
        $user = auth()->user();
        if (empty($user)) {
            return response()->json([
                'result' => false,
                'message' => '',
                'user_details' => $user,
            ], 401);
        }
        $dbArray = [];
        $user = User::find($user->id);
        if (!empty($request->name)) {
            $user->name = $request->name;
        }
        if (!empty($request->dob)) {
            $user->dob = date('Y-m-d',strtotime($request->dob));
        }
        if (!empty($request->gender)) {
            $user->gender = $request->gender;
        }
        if (!empty($request->email)) {
            $user->email = $request->email;
        }
        if (!empty($request->income_type)) {
            $user->income_type = $request->income_type;
        }
        if (!empty($request->pancard)) {
            $user->pan_card = $request->pancard;
        }
        if (!empty($request->address)) {
            $user->address = $request->address;
        }
        if (!empty($request->state)) {
            $user->state = $request->state;
        }
        if (!empty($request->pincode)) {
            $user->pincode = $request->pincode;
        }if (!empty($request->residence_type)) {
            $user->residence_type = $request->residence_type;
        }if (!empty($request->alternate_no)) {
            $user->alternate_no = $request->alternate_no;
        }if (!empty($request->referene_contact)) {
            $user->referene_contact = $request->referene_contact;
        }if (!empty($request->residing_with_family)) {
            $user->residing_with_family = $request->residing_with_family;
        }
        if (!empty($request->marriage_status)) {
            $user->marriage_status = $request->marriage_status;
        }
        if (!empty($request->education)) {
            $user->education = $request->education;
        }
        if (!empty($request->gender)) {
            $user->gender = $request->gender;
        }
        
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = dirname(__DIR__, 6) . "/public/storage/user/";
            $file_name = $this->upload_image($user->id, $file, $path, true);
            $user->profile_picture = $file_name;
        }
        $user->save();
        $user->profile_picture = $this->getImageUrl('user', $user->profile_picture);
        return response()->json([
            'result' => true,
            'message' => 'User Profile Updated Successfully',
            'user_details' => $user,
        ], 200);
    }




    public function home(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            // 'exam_category' => 'required'
        ]);
        $home_data = null;
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
                'home_data' => $home_data,
            ], 400);
        }
        $user = auth()->user();
        if (empty($user)) {
            return response()->json([
                'result' => false,
                'message' => '',
                'home_data' => $home_data,
            ], 401);
        }
        $banners = Banner::where('status', 1)->where('category_id', $request->exam_category)->where('is_delete', 0)->get();
        if (!empty($banners)) {
            foreach ($banners as $banner) {
                $banner->image = $this->getImageUrl('banners', $banner->image);
                $banner->category_id = $banner->category_id ?? '';
                $banner->course_id = $banner->course_id ?? '';
                $banner->link = $banner->link ?? '';
                $banner->type = $banner->type ?? '';
            }
        }

        $loans =[];

        $home_data['banners'] = $banners;
        $home_data['loans'] = $loans;

        return response()->json([
            'result' => true,
            'message' => 'Home Data',
            'home_data' => $home_data,

        ], 200);
    }





    public function apply_loan(Request $request)
    {


        $validator =  Validator::make($request->all(), [
            // 'exam_category' => 'required'
        ]);
        $home_data = null;
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
            ], 400);
        }
        $user = auth()->user();
        if (empty($user)) {
            return response()->json([
                'result' => false,
                'message' => '',
            ], 401);
        }
        $user = User::find($user->id);
        $dbArray = [];

        if (!empty($request->salary_get)) {
            $dbArray['salary_get'] = $request->salary_get;
        }
        if (!empty($request->existing_emi)) {
            $dbArray['existing_emi'] = $request->existing_emi;
        }
        if (!empty($request->loan_amount)) {
            $dbArray['loan_amount'] = $request->loan_amount;
        }
        if (!empty($request->tenure)) {
            $dbArray['tenure'] = $request->tenure;
        }
        if (!empty($request->purpose_of_loan)) {
            $dbArray['purpose_of_loan'] = $request->purpose_of_loan;
        }
        if (!empty($request->company_name)) {
            $dbArray['company_name'] = $request->company_name;
        }
        if (!empty($request->employeement_type)) {
            $dbArray['employeement_type'] = $request->employeement_type;
        }
        if (!empty($request->monthly_salary)) {
            $dbArray['monthly_salary'] = $request->monthly_salary;
        }
        if (!empty($request->office_address)) {
            $dbArray['office_address'] = $request->office_address;
        }
        if (!empty($request->state_id)) {
            $dbArray['state_id'] = $request->state_id;
        }
        if (!empty($request->city_id)) {
            $dbArray['city_id'] = $request->city_id;
        }
        if (!empty($request->pincode)) {
            $dbArray['pincode'] = $request->pincode;
        }
        if (!empty($request->website)) {
            $dbArray['website'] = $request->website;
        }
        if (!empty($request->official_mail)) {
            $dbArray['official_mail'] = $request->official_mail;
        }
        $dbArray['status'] = 0;
        $dbArray['user_id'] = $user->id;
        $dbArray['loan_id'] = 'AS'.rand(1111111,999999999);

        $loan_id = LoanDetails::insertGetId($dbArray);
        $bankDetailsArr = [];
        $bankDetailsArr['user_id'] = $user->id;
        $bankDetailsArr['loan_id'] = $loan_id;
        if (!empty($request->account_holder_name)) {
            $bankDetailsArr['account_holder_name'] = $request->account_holder_name;
        }
        if (!empty($request->account_no)) {
            $bankDetailsArr['account_no'] = $request->account_no;
        }
        if (!empty($request->ifsc_code)) {
            $bankDetailsArr['ifsc_code'] = $request->ifsc_code;
        }
        if (!empty($request->bank_name)) {
            $bankDetailsArr['bank_name'] = $request->bank_name;
        }
        if (!empty($request->branch)) {
            $bankDetailsArr['branch'] = $request->branch;
        }
        $bankDetailsArr['status'] = 1;
        $bank_id = BankDetails::insertGetId($bankDetailsArr);

        $documentArr = [];
        if ($request->hasFile('adhar_front')) {
            $file = $request->file('adhar_front');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $adhar_front = $this->upload_image($user->id, $file, $path, true);
            $documentArr['adhar_front'] = $adhar_front;
        }
        if ($request->hasFile('adhar_back')) {
            $file = $request->file('adhar_back');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $adhar_back = $this->upload_image($user->id, $file, $path, true);
            $documentArr['adhar_back'] = $adhar_back;
        }
        if ($request->hasFile('pan')) {
            $file = $request->file('pan');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $pan = $this->upload_image($user->id, $file, $path, true);
            $documentArr['pan'] = $pan;
        }
        if ($request->hasFile('selfi')) {
            $file = $request->file('selfi');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $selfi = $this->upload_image($user->id, $file, $path, true);
            $documentArr['selfi'] = $selfi;
        }
        if ($request->hasFile('bank_statement')) {
            $file = $request->file('bank_statement');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $bank_statement = $this->upload_image($user->id, $file, $path, true);
            $documentArr['bank_statement'] = $bank_statement;
        }
        if ($request->hasFile('salary_slip')) {
            $file = $request->file('salary_slip');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $salary_slip = $this->upload_image($user->id, $file, $path, true);
            $documentArr['salary_slip'] = $salary_slip;
        }
        if ($request->hasFile('id_card')) {
            $file = $request->file('id_card');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $id_card = $this->upload_image($user->id, $file, $path, true);
            $documentArr['id_card'] = $id_card;
        }
        if ($request->hasFile('voter_id_card_front')) {
            $file = $request->file('voter_id_card_front');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $voter_id_card_front = $this->upload_image($user->id, $file, $path, true);
            $documentArr['voter_id_card_front'] = $voter_id_card_front;
        }
        if ($request->hasFile('voter_id_card_back')) {
            $file = $request->file('voter_id_card_back');
            $path = dirname(__DIR__, 6) . "/public/storage/user_documents/";
            $voter_id_card_back = $this->upload_image($user->id, $file, $path, true);
            $documentArr['voter_id_card_back'] = $voter_id_card_back;
        }

        UserDocument::insertGetId($documentArr);
        return response()->json([
            'result' => true,
            'message' => 'Loan Applied Successfully',

        ], 200);
    }



    public function send_sms($mobile, $otp)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"flow_id\": \"61e559bdd8caa36a6b0d0c53\",\n  \"sender\": \"TEKGLO\",\n  \"mobiles\": \"91$mobile\",\n  \"otp\": \"$otp\",\n  \"tekniko\": \"Tekniko\"\n}",
            CURLOPT_HTTPHEADER => [
                "authkey: 285140ArLurg2KnR61e3d660P1",
                "content-type: application/JSON"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //echo  $response;
        return $response;
    }



    public function cms_pages(Request $request)
    {
        $validator =  Validator::make($request->all(), [

            'type' => 'required',
        ]);
        $pages = null;
        $user = null;

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
                'pages' => $pages,
            ], 400);
        }

        if (!empty($request->token)) {
            $user = auth()->user();
            if (empty($user)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Not authenticate',

                ], 401);
            }
        }

        $title = '';
        $cms = DB::table('settings')->where('id', 1)->first();
        if ($request->type == 'contactus') {
            $pages = $cms->contact_phone ?? '';
            $title = 'Contact Us';
        }
        if ($request->type == 'about_us') {
            $pages = $cms->about_us ?? '';
            $title = 'About Us';
        }
        if ($request->type == 'privacypolicy') {
            $pages = $cms->privacypolicy ?? '';
            $title = 'Privacy Policy';
        }
        if ($request->type == 'terms') {
            $pages = $cms->terms ?? '';
            $title = 'Terms & Condition';
        }

        if ($request->type == 'contact_email') {
            $pages = $cms->contact_email ?? '';
        }
        if ($request->type == 'refund') {
            $pages = $cms->refund ?? '';
            $title = 'Refund Policy';
        }

        if ($request->type == 'contact_phone') {
            $pages = $cms->contact_phone ?? '';
        }
        return response()->json([
            'result' => true,
            'message' => 'CMS Page Detail',
            'details' => $pages,
            'title' => $title,
        ], 200);
    }











}
