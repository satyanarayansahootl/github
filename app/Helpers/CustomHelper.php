<?php

namespace App\Helpers;
use DB;
use Mail;
use Storage;
use Auth;
use Validator;
use Image;
use App\Models\ExamCategory;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\Admin;
use App\Models\Types;
use App\Models\User;
use App\Models\SubCategory;
use App\Models\Comments;
use App\Models\Subject;
use App\Models\Permission;
use App\Models\News;
use App\Models\Chapter;

use DateTime;


class CustomHelper{

    /**
     * Render S3 image URL
     *
     * @param $name
     * @param bool $thumbnail
     * @return string
     */

    public static function getAdminRouteName(){
        $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');

        if(empty($ADMIN_ROUTE_NAME)){
            $ADMIN_ROUTE_NAME = 'admin';
        }

        return $ADMIN_ROUTE_NAME;
    }


    public static function getCategoryName($category_id){
        $categories = ExamCategory::where('id',$category_id)->first();

        return $categories->name??'';
    }  
 public static function getChapterName($id){
        $chapter = Chapter::where('id',$id)->first();

        return $chapter->chapter_name??'';
    } 

      public static function getSubjectName($id){
        $subject = Subject::where('id',$id)->first();

        return $subject->subject_name??'';
    }  

    public static function getExamCategory(){
        $categories = ExamCategory::where('status',1)->where('is_delete',0)->get();

        return $categories;
    }  

    // public static function getCourses(){
    //     $courses = Course::where('is_delete',0)->where('status',1)->get();

    //     return $courses;
    // }  

    public static function getSubCategoryName($sub_cat_id){
        $categories = SubCategory::where('id',$sub_cat_id)->first();

        return $categories->name??'';
    }
    public static function getCourseDetails($course_id){
        $course = Course::where('id',$course_id)->first();
        return $course;
    } 
    public static function getCourseName($course_id){
        $course = Course::where('id',$course_id)->first();

        return $course->name??'';
    }
    public static function getAdminName($user_id){
        $admin = Admin::where('id',$user_id)->first();

        return $admin->name??'';
    }
    public static function getUserDetails($user_id){
        $user = User::where('id',$user_id)->first();

        return $user;
    }

    public static function getLiveClassTypes(){
        $types = config('custom.live_class_types');
        return $types;
    }


    public static function getFaculties(){
        $admins = Admin::where('role_id',1)->get();
        return $admins;
    }

    public static function getNewsCommentCount($news_id){
        $count = Comments::where('news_id',$news_id)->count();
        return $count;
    }

    public static function getBlogs($key){
        if($key == 'all'){
            $blogs = News::where('status',1)->where('is_delete',0)->get();
        }else{
            $blogs = News::where('type',$key)->where('status',1)->where('is_delete',0)->get();

        }
        return $blogs;
    }



    public static function getNewsType(){
        $news_types = config('custom.news_type');
        // if(!empty($news_types)){
        //     foreach ($news_types as $key => $value) {

        //     }
        // }
        return $news_types;
    }
    public static function getNewsTypeName($key){
       $news_types = config('custom.news_type');
       $name = '';
       if(!empty($news_types)){
        foreach ($news_types as $key1 => $value) {
            if($key == $key1){
                $name = $value;
            }
        }
    }
    return $name;
}







public static function getTypesOfCourse($course_id){
    $html ='';
    $type_ids = CourseType::where('course_id',$course_id)->pluck('type_id')->toArray();
    if(!empty($type_ids)){
        $types = Types::whereIn('id',$type_ids)->get();
        if(!empty($types)){
            foreach($types as $type){
                $html.=$type->name??'';
                $html.='<br>';
            }
        }
    }
    return $html;
}

public static function getTypeIdsCourse($course_id){
    $type_ids = CourseType::where('course_id',$course_id)->pluck('type_id')->toArray();
    return $type_ids;
}



public static function isAllowedSection($sectionName, $type=''){
    $roleId = Auth::guard('admin')->user()->role_id; 
    $isAllowed = false;
    if($roleId == 0){
        $isAllowed =  true;
    }else{
     $sectionpermission = Permission::where('role_id',$roleId)->where('section',$sectionName)->where($type,1)->first();
     if(!empty($sectionpermission)){
        $isAllowed = true;
    }else{
        $isAllowed = false;
    }
}
return $isAllowed;
}







public static function isAllowedModule($moduleName){
   // prd($moduleName);
    $isAllowed = false;
    $allowedModulesArr = config('modules.allowed');
    $moduleNameArrAnd = [];
    $moduleNameArrOr = [];

    $isAnd = strpos($moduleName, "&");
    $isOr = strpos($moduleName, "|");

    if($isAnd >= 0 && $isAnd !== false){
        $moduleNameArrAnd = explode('&', $moduleName);
    }
        //elseif($isOr >= 0 && $isOr !== false){
    else{
        $moduleNameArrOr = explode('|', $moduleName);
    }

        //pr($moduleNameArr);
        //prd($moduleNameArr);        

    if(!empty($moduleNameArrAnd) && count($moduleNameArrAnd) > 0){
        $isAndAllowed = true;
        foreach($moduleNameArrAnd as $module){
            if(!in_array($module, $allowedModulesArr)){
                $isAndAllowed = false;
            }
        }

        $isAllowed = $isAndAllowed;
    }
    elseif(!empty($moduleNameArrOr) && count($moduleNameArrOr) > 0){
        foreach($moduleNameArrOr as $module){
            if(in_array($module, $allowedModulesArr)){
                return true;
            }
        }
    }



    return $isAllowed;
}

public static function getImageUrl($path,$filename){
    $storage = Storage::disk('public');

 if(!empty($filename) ){
    return url('/')."/public/storage/".$path.'/'.$filename;
}else{
    return url('/public/storage/settings/appicon.png');
}
}



public static function getSubCategory($category_id=''){
    $exams = SubCategory::where('status', 1)->where('is_delete', 0);
    if(!empty($category_id)){
        $exams->where('category_id', $category_id);
    }
    $exams = $exams->get();

    return $exams;
}

public static function getPastTime($timestamp){
            $date1 = new DateTime($timestamp);
            $date2 = new DateTime(date('Y-m-d h:i:s'));

            $difference = $date1->diff($date2);
            
            if($difference->s <= 60){
                $data = self::convert_number_to_words($difference->s) . " Second Ago"; //23
            }

            if($difference->i <= 60){
                $data = self::convert_number_to_words($difference->i) . " Minute Ago"; //23
            }
            if($difference->h >= 1 && $difference->h <= 24){
                $data = self::convert_number_to_words($difference->h) . " Hour Ago"; //23
            }
            if($difference->d >= 1  && $difference->h <= 30){
                $data = self::convert_number_to_words($difference->d) . " Day Ago"; //23
            }
            if($difference->m >= 1 && $difference->m <= 12){
                $data = self::convert_number_to_words($difference->m) . " Month Ago"; //23
            }
            if($difference->y >= 1){
                $data = self::convert_number_to_words($difference->y) . " Year Ago"; //23
            }

            return $data;


            // $diffInDays    = $difference->d; //21
            // $diffInMonths  = $difference->m; //4
            // $diffInYears   = $difference->y; //1
}



public static function getCourses($category_id='',$subcategory_id='',$type_id='',$search=''){
    $courses = Course::where('status', 1)->where('is_delete', 0);
        if (!empty($category_id)) {
            $courses->whereRaw("FIND_IN_SET(?, category_id) > 0", [$category_id]);
        }
        if (!empty($subcategory_id)) {
            $courses->whereRaw("FIND_IN_SET(?, subcategory_id) > 0", [$subcategory_id]);
        }
        if (!empty($search)) {
            $courses->where('name', 'like', '%' . $search . '%');
        }
        if(!empty($type_id) && $type_id !=null){
            $course_ids = CourseType::where('type_id',$type_id)->pluck('course_id')->toArray();
            $courses->whereIn('id',$course_ids);
        }

        $courses = $courses->get();

        return $courses;

}


public static function GetSlugBySelf($slug_array, $text) {

    $slug = '';

    // echo $text; die;
    // replace non letter or digits by -
    $text = preg_replace ( '~[^\pL\d]+~u', '-', $text );

    // transliterate
    $text = iconv ( 'utf-8', 'us-ascii//TRANSLIT', $text );

    // remove unwanted characters
    $text = preg_replace ( '~[^-\w]+~', '', $text );

    // trim
    $text = trim ( $text, '-' );

    // remove duplicate -
    $text = preg_replace ( '~-+~', '-', $text );

    // lowercase
    $text = strtolower ( $text );
    // echo $text; die;
    if (empty ( $text )) {
    // return 'n-a';
    }

    $slug = self::GetUniqueSlugBySelf ( $slug_array, $text );
    // echo $slug; die;

    return $slug;
}

public static function GetUniqueSlugBySelf($slug_array, $slug = '', &$num = '') {

    $new_slug = $slug . $num;

        //pr($new_slug);

    $slug = $new_slug;

    if(is_array($slug_array) && in_array($slug, $slug_array)){
        $num = (int)$num + 1;
        $slug = self::GetUniqueSlugBySelf ( $slug_array, $new_slug, $num );
    }

    return $slug;
}




public static function GetSlug($tbl_name, $id_field, $row_id = '', $text = '') {
    // echo $text; die;
    // replace non letter or digits by -
    $text = preg_replace ( '~[^\pL\d]+~u', '-', $text );

    // transliterate
    $text = iconv ( 'utf-8', 'us-ascii//TRANSLIT', $text );

    // remove unwanted characters
    $text = preg_replace ( '~[^-\w]+~', '', $text );

    // trim
    $text = trim ( $text, '-' );

    // remove duplicate -
    $text = preg_replace ( '~-+~', '-', $text );

    // lowercase
    $text = strtolower ( $text );
    // echo $text; die;
    if (empty ( $text )) {
    // return 'n-a';
    }
    // echo $text; die;
    $slug = self::GetUniqueSlug ( $tbl_name, $id_field, $row_id, $text );
    // echo $slug; die;
    return $slug;
}

public static function GetUniqueSlug($tbl_name, $id_field, $row_id = '', $slug = '', &$num = '') {

//prd($num);

    $new_slug = $slug . $num;

    $query = DB::table($tbl_name);
    $query->where('slug', $new_slug);
    $row = $query->first();

    if (empty ( $row )) {
        $slug = $new_slug;
    } else {
// echo 'here'; die;
        if (! empty ( $row_id ) && $row->$id_field == $row_id) {
            $slug = $new_slug;
        } else {
            $num = (int)$num + 1;
            $slug = self::GetUniqueSlug ( $tbl_name, $id_field, $row_id, $new_slug, $num );
        }
    }
    return $slug;
}

public static function getStatusStr($status){

    if(is_numeric($status) && strlen($status) > 0){
        if($status == 1){
            $status = 'Active';
        }
        else{
            $status = 'Inactive';
        }

    }
    return $status;
}

public static function getStatusHTML($status, $tbl_id=0, $class='', $id='', $type='status', $activeTxt='Active', $inActiveTxt='In-active'){

    $status_str = '';

    if(is_numeric($status) && strlen($status) > 0){
        $status_name = '';
        $a_label = '';

        if($status == 1){
            $status_name = $activeTxt;
            $a_label = 'label-success';
        }
        else{
            $status_name = $inActiveTxt;
            $a_label = 'label-warning';
        }
        $status_str = '<a href="javascript:void(0)" class="label '.$a_label.' '.$class.'" id="'.$id.'" data-id="'.$tbl_id.'" data-status="'.$status.'" data-type="'.$type.'" >'.$status_name.'</a>';
    }

    if(empty($status_str)){
        $status_str = $status;
    }

    return $status_str;
}


public static function CheckAndFormatDate($date, $toFormat='Y-m-d H:i:s', $fromFormat=''){
    $new_date = $date;

    $date = preg_replace(array('/\//', '/\./'), '-', $date);

    //echo $date; die;

    $new_date = self::DateFormat($date, $toFormat, $fromFormat='y-m-d');

    return $new_date;
}

public static function DateFormat($date, $toFormat='Y-m-d H:i:s', $fromFormat=''){

    $new_date = $date;

    $formatArr = array('d-m-y', 'd-m-Y', 'd/m/Y', 'd/m/y', 'd/m/Y H:i:s', 'd/m/y H:i:s', 'd/m/Y H:i A', 'd/m/y H:i A',);

    if(empty($toFormat)){
        $toFormat='Y-m-d H:i:s';
    }

    if($date != '0000-00-00 00:00:00' && $date != '0000-00-00' && $date != ''){
        if(empty($fromFormat) || $fromFormat == '' || !in_array($fromFormat, $formatArr)){
            $new_date = date($toFormat, strtotime($date));         
        }
        elseif($fromFormat == 'd-m-y' || $fromFormat == 'd-m-Y'){
            $date_arr = explode('-', $date);
            $date_str = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];
            $new_date = date($toFormat, strtotime($date_str));
        }
        elseif($fromFormat == 'd/m/Y' || $fromFormat == 'd/m/y'){
            $datetime_arr = explode(' ', $date);

            $date_arr = explode('/', $datetime_arr[0]);
            $date_str = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];

            $new_date = date($toFormat, strtotime($date_str));
        }
        elseif($fromFormat == 'd/m/Y H:i:s' || $fromFormat == 'd/m/y H:i:s'){
            $datetime_arr = explode(' ', $date);

            $time = $datetime_arr[1];

            $date_arr = explode('/', $datetime_arr[0]);
            $date_str = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];

            $new_date = date($toFormat, strtotime($date_str.' '.$time));
        }
        elseif($fromFormat == 'd/m/Y H:i A' || $fromFormat == 'd/m/y H:i A'){
            $datetime_arr = explode(' ', $date);

            $time = $datetime_arr[1].' '.$datetime_arr[2];

            $date_arr = explode('/', $datetime_arr[0]);
            $date_str = $date_arr[2].'-'.$date_arr[1].'-'.$date_arr[0];

            $new_date = date($toFormat, strtotime($date_str.' '.$time));
        }

    }
    else{
        $new_date = '';
    }

    return $new_date;
}

public static function DateDiff($date1, $date2){

    $date_diff = '';

    $date1 = Self::DateFormat($date1, 'Y-m-d');
    $date2 = Self::DateFormat($date2, 'Y-m-d');

    if(!empty($date1) && !empty($date2)){
        $date1 = date_create($date1);
        $date2 = date_create($date2);
        $diff = date_diff($date1,$date2);

        $date_diff = $diff->format("%a");
    }
    return $date_diff;
}

public static function getStartAndEndDateOfWeek($week, $year, $format='Y-m-d H:i:s') {
    $dateTime = new \DateTime();
    $dateTime->setISODate($year, $week);
    $result['start_date'] = $dateTime->format($format);
    $dateTime->modify('+6 days');
    $result['end_date'] = $dateTime->format($format);
    return $result;
}

/* Note: this function requires laravel intervention/image package */
public static function UploadImage($file, $path, $ext='', $width=768, $height=768, $is_thumb=false, $thumb_path, $thumb_width=300, $thumb_height=300){

    if(empty($ext)){
        $ext='jpg,jpeg,png,gif,pdf';
    }
    
    list($img_width, $img_height, $type, $attr) = getimagesize($file);
        //prd($image_info);

    if($img_width < $width){
        $width = $img_width;
    }

    if($img_height < $height){
        $height = $img_height;
    }

        //echo url('public/uploads'); die;

    $result['success'] = false;

    $result['org_name'] = '';
    $result['file_name'] = '';

    if ($file) {

            //$path = 'designs/';
            //$thumb_path = 'designs/thumb/';

        $validator = Validator::make(['file' => $file], ['file' => 'mimes:'.$ext]);

        if ($validator->passes()) {
            $handle = fopen($file, "r");
            $opening_bytes = fread($handle, filesize($file));

            fclose($handle);

            if( strlen(strpos($opening_bytes,'<?php')) > 0 && (strpos($opening_bytes,'<?php') >= 0 || strpos($opening_bytes,'<?PHP') >= 0) )
            {
                $result['errors']['file'] = "Invalid image!";
            }
            else{

                $extension = $file->getClientOriginalExtension();
                $fileOriginalName = $file->getClientOriginalName();
                $fileOriginalName = str_replace(' ', '', $fileOriginalName);
                $fileName = date('dmyhis').'-'.$fileOriginalName;

                $is_uploaded = Image::make($file)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('storage/'.$path . $fileName));

                if($is_uploaded){

                    $result['success'] = true;

                    if($is_thumb){
                        $thumb = Image::make($file)->resize($thumb_width, $thumb_height, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save(public_path('storage/'.$thumb_path . $fileName));
                    }

                    $result['org_name'] = $fileOriginalName;
                    $result['file_name'] = $fileName;
                    $result['extension'] = $extension ?? '';
                }
            }
        }
        else{
            $result['errors'] = $validator->errors();
        }

    }

    return $result;
}

public static function UploadFile($file, $path, $ext=''){

    if(empty($ext)){
        $ext='jpg,jpeg,png,gif,doc,docx,txt,pdf,xls,xlsx,mp4';
    }

    $path = 'public/'.$path;

    $result['success'] = false;

    $result['org_name'] = '';
    $result['file_name'] = '';
    $result['file_path'] = '';

    if ($file) {

        $validator = Validator::make(['file' => $file], ['file' => 'mimes:'.$ext]);

        if ($validator->passes()) {
            $handle = fopen($file, "r");
            $opening_bytes = fread($handle, filesize($file));

            fclose($handle);

            if( strlen(strpos($opening_bytes,'<?php')) > 0 && (strpos($opening_bytes,'<?php') >= 0 || strpos($opening_bytes,'<?PHP') >= 0) ){
                $result['errors']['file'] = "Invalid file!";
            }
            else{
                $extension = $file->getClientOriginalExtension();
                $fileOriginalName = $file->getClientOriginalName();
                $fileName = date('dmyhis').'-'.trim($fileOriginalName);

                $path = $file->storeAs($path, $fileName);

                if($path){
                    $result['success'] = true;

                    $result['org_name'] = $fileOriginalName;
                    $result['file_name'] = $fileName;
                    $result['file_path'] = $path;
                    $result['extension'] = $extension;
                }
            }
        }
        else{
            $result['errors'] = $validator->errors();
        }

    }
    return $result;
    
}



public static function UploadFileNew($file, $path, $ext=''){

    if(empty($ext)){
        $ext='jpg,jpeg,png,gif,doc,docx,txt,pdf,xls,xlsx,mp4';
    }

    $path = 'public/'.$path;

    $result['success'] = false;

    $result['org_name'] = '';
    $result['file_name'] = '';
    $result['file_path'] = '';

    if ($file) {

        $validator = Validator::make(['file' => $file], ['file' => 'required']);

        if ($validator->passes()) {
            $handle = fopen($file, "r");
            $opening_bytes = fread($handle, filesize($file));

            fclose($handle);

            if( strlen(strpos($opening_bytes,'<?php')) > 0 && (strpos($opening_bytes,'<?php') >= 0 || strpos($opening_bytes,'<?PHP') >= 0) ){
                $result['errors']['file'] = "Invalid file!";
            }
            else{
                $extension = $file->getClientOriginalExtension();
                $fileOriginalName = $file->getClientOriginalName();
                    // $fileName = date('dmyhis').'-'.$fileOriginalName;
                $fileName = date('dmyhis').'-'.str_replace(' ', '', $fileOriginalName);;

                $path = $file->storeAs($path, $fileName);

                if($path){
                    $result['success'] = true;

                    $result['org_name'] = $fileOriginalName;
                    $result['file_name'] = $fileName;
                    $result['file_path'] = $path;
                    $result['extension'] = $extension;
                }
            }
        }
        else{
            $result['errors'] = $validator->errors();
        }

    }
    return $result;
        // print_r($result);
        // die;
}


public static function WebsiteSettings($name){

    $value = '';
    $settings = DB::table('website_settings')->where('name', $name)->first();
    
    if(!empty($settings) && isset($settings->value)){
        $value = $settings->value;
    }
    return $value;
}


public static function websiteSettingsArray($nameArr){

    $settings = '';

    if(is_array($nameArr) && !empty($nameArr) && count($nameArr) > 0){
        $settings = DB::table('website_settings')->whereIn('name', $nameArr)->get()->keyBy('name');
            //prd($settings);
    }
    return $settings;
}


public static function formatUserAddress($userAddr){

    $addressArr = [];

    if(!empty($userAddr) && count($userAddr) > 0){

        $address = $userAddr->address;
        $locality = $userAddr->locality;
        $pincode = $userAddr->pincode;

        if(!empty($address)){
            $addressArr[] = $address;
        }
        if(!empty($locality)){
            $addressArr[] = $locality;
        }

        $addressState = '';
        $addressCity = '';

        if(isset($userAddr->userState)){
            $addressState = $userAddr->userState;
        }
        elseif($userAddr->addressState){
            $addressState = $userAddr->addressState;
        }

        if(isset($userAddr->userCity)){
            $addressCity = $userAddr->userCity;
        }
        elseif($userAddr->addressCity){
            $addressCity = $userAddr->addressCity;
        }

            /*$addressState = ($userAddr->addressState)?$userAddr->addressState:'';
            $addressCity = ($userAddr->addressCity)?$userAddr->addressCity:'';*/

            if(!empty($addressState) && count($addressState) > 0){
                if(!empty($addressState->name)){
                    $addressArr[] = $addressState->name;
                }
            }

            if(!empty($addressCity) && count($addressCity) > 0){
                if(!empty($addressCity->name)){
                    $addressArr[] = $addressCity->name;
                }
            }

            if(!empty($pincode)){
                $addressArr[] = 'Pincode:'.$pincode;
            }

        }
        return $addressArr;
    }


    public static function formatOrderAddress($order, $isBilling=true, $isPhone=true, $isEmail=true){

        $orderAddrArr = [];

        if(!empty($order) && count($order) > 0){

            $name = '';
            $address = '';
            $locality = '';
            $pincode = '';
            $cityName = '';
            $stateName = '';
            $countryName = '';
            $phone = '';
            $email = '';

            if($isBilling){

                $name = $order->billing_name;
                $address = $order->billing_address;
                $locality = $order->billing_locality;
                $pincode = $order->billing_pincode;

                $billingCity = $order->billingCity;
                $billingState = $order->billingState;
                $billingCountry = $order->billingCountry;

                if(isset($billingCity->name) && !empty($billingCity->name)){
                    $cityName = $billingCity->name;
                }
                if(isset($billingState->name) && !empty($billingState->name)){
                    $stateName = $billingState->name;
                }
                if(isset($billingCountry->name) && !empty($billingCountry->name)){
                    $countryName = $billingCountry->name;
                }

                $phone = $order->billing_phone;
                $email = $order->billing_email;

            }
            else{
                $name = $order->shipping_name;
                $address = $order->shipping_address;
                $locality = $order->shipping_locality;
                $pincode = $order->shipping_pincode;

                $shippingCity = $order->shippingCity;
                $shippingState = $order->shippingState;
                $shippingCountry = $order->shippingCountry;


                if(isset($shippingCity->name) && !empty($shippingCity->name)){
                    $cityName = $shippingCity->name;
                }
                if(isset($shippingState->name) && !empty($shippingState->name)){
                    $stateName = $shippingState->name;
                }
                if(isset($shippingCountry->name) && !empty($shippingCountry->name)){
                    $countryName = $shippingCountry->name;
                }

                $phone = $order->shipping_phone;
                $email = $order->shipping_email;
            }

            if(!empty($name)){
                $orderAddrArr[] = $name;
            }

            if(!empty($address)){
                $orderAddrArr[] = $address;
            }

            if(!empty($cityName) && !empty($pincode)){
                $cityName = $cityName.'-'.$pincode;
            }

            $cityArr = [];

            if(!empty($locality)){
                $cityArr[] = $locality;
            }
            if(!empty($cityName)){
                $cityArr[] = $cityName;
            }

            $orderAddrArr[] = implode(', ', $cityArr);

            $countryArr = [];

            if(!empty($stateName)){
                $countryArr[] = $stateName;
            }
            if(!empty($countryName)){
                $countryArr[] = $countryName;
            }

            $orderAddrArr[] = implode(', ', $countryArr);

            if($isPhone && !empty($phone)){
                $orderAddrArr[] = '<span class="addr_label">Phone: </span>'.$phone;
            }

            if($isEmail && !empty($email)){
                $orderAddrArr[] = '<span class="addr_label">Email: </span>'.$email;
            }

        }
        return $orderAddrArr;
    }



    public static function GetCountry($id=0, $col_name=''){

        $value = '';

        if(is_numeric($id) && $id > 0){
            $country = DB::table('countries')->where('id', $id)->first();

            if(!empty($col_name) && isset($country->{$col_name})){
                $value = $country->{$col_name};
            }
            else{
                $value = $country;
            }
        }

        return $value;
    }

    
    public static function GetParentCategory($category){
        //echo "categoryParentForBreadcrumb=";

        //prd($category->toArray());

        $parent = '';

        if( isset($category->parent) && count($category->parent) > 0 ){
            $parent = $category->parent;            
        }

        //prd($parents_arr);
        return $parent;
    }

    public static function GetParentCategories($id='', $type='', $params=array()){
        $categories = '';

        $orderBy = (isset($params['orderBy']) && ( $params['orderBy'] == 'desc' || $params['orderBy'] == 'asc' ))?$params['orderBy']:'asc';

        $order_type = (isset($params['order_type']) && ( $params['order_type'] == 'desc' || $params['order_type'] == 'asc' ))?$params['order_type']:'asc';

        $category_query = Category::where('status', 1);

        if($type == 'design'){
            $category_query->where('parent_id', 0);
        }

        if(!empty($type)){
            $category_query->where('type', $type);
        }

        if(isset($params['orderBy']) && !empty($params['orderBy'])){
            $category_query->orderBy($params['orderBy'], $order_type);
        }

        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $category_query->limit($params['limit']);
        }

        if(is_numeric($id) && $id > 0){
            $category_query->where('id', $id);
            $categories = $category_query->first();
        }
        else{
            $categories = $category_query->get();
        }

        return $categories;
    }

    public static function getCategoriesold($id='', $parent_id=0, $params=array()){

        $categories = '';

        $category_query = Category::where('status', 1);
        $category_query->where('parent_id', $parent_id);

        if(!empty($id)){
            //$category_query->where('id', $id);
            $category_query->where(function ($query) use ($id) {
                $query->where('id', $id)
                ->orWhere('slug', $id);
            });
            $categories = $category_query->first();
        }
        else{
            $categories = $category_query->orderBy('sort_order', 'asc')->get();
        }

        return $categories;
    }


    public static function CategoriesMenu($type='', $className='', $idName=''){
        $CatParams = array();
        $CatParams['orderBy'] = 'sort_order';
        $CatParams['order_type'] = 'asc';

        $ParentCategories = Self::GetParentCategories('', $type, $CatParams);

        //pr($ParentCategories); die;
        $all_menu = url('designs');
        $menu_list = '';
        $menu_list .= '<ul class="'.$className.'" id="'.$idName.'">';

        $menu_list .= '<li><a href="'.$all_menu.'">All Designs</a></li>';
        $menu_list .= '<li><a href="#">All Best Sellers</a></li>';

        if(!empty($ParentCategories) && count($ParentCategories)){

            foreach($ParentCategories as $parentCat){

                $childrenCat = $parentCat->children;

                $cat_url = url('designs?cat='.$parentCat->slug);

                if(isset($childrenCat) && count($childrenCat) > 0){
                    $cat_url = 'javascript:void(0)';
                }

                $menu_list .= '<li><a href="'.$cat_url.'">'.$parentCat->name.'</a>';

                if(isset($childrenCat) && count($childrenCat) > 0){

                    $childrenCat = $childrenCat->sortBy('sort_order');

                    $menu_list .= Self::CategoriesMenuChild($childrenCat, $className, $idName);
                }
                $menu_list .= '</li>';

            }

        }
        $menu_list .= '</ul>';

        return $menu_list;
    }

    public static function CategoriesMenuChild($childCategories, $className='', $idName=''){
        $menu_list_child = '';

        if(!empty($childCategories) && count($childCategories) > 0){
            $menu_list_child .= '<ul class="'.$className.'" id="'.$idName.'">';

            foreach($childCategories as $childCat){

                $childrenCat = $childCat->children;

                $cat_url = url('designs?cat='.$childCat->slug);

                if(isset($childrenCat) && count($childrenCat) > 0){
                    $cat_url = 'javascript:void(0)';
                }

                $menu_list_child .= '<li><a href="'.$cat_url.'">'.$childCat->name.'</a>';

                if(isset($childrenCat) && count($childrenCat) > 0){

                    $childrenCat = $childrenCat->sortBy('sort_order');

                    $menu_list_child .= Self::CategoriesMenuChild($childrenCat, $className, $idName);
                }
                $menu_list_child .= '</li>';

            }

            $menu_list_child .= '</ul>';
        }

        return $menu_list_child;
    }



    private static $parentCatArr = [];

    public static function categoryParentForBreadcrumb($category){

        if( isset($category->parent) && count($category->parent) > 0 ){
            $parent_category = $category->parent;

            Self::$parentCatArr[] = $parent_category->toArray();

            if( isset($parent_category->parent) && count($parent_category->parent) > 0 ){
                Self::categoryParentForBreadcrumb($parent_category);
            }
            
        }
    }


    public static function CategoryBreadcrumb($category, $first_uri, $first_uri_name, $is_last_link = false){

        Self::$parentCatArr = [];

        $BackUrl = Self::BackUrl();

        //prd($category->toArray());
        $breadcrumb = '';

        if(!empty($first_uri_name)){
            $breadcrumb .= '<a href="'.url($first_uri).'" class="btn-link" >'.$first_uri_name.'</a>';
        }

        $hierarchy_arr = [];

        if(!empty($category) && count($category) > 0){

            Self::categoryParentForBreadcrumb($category);

            $hierarchy_arr = Self::$parentCatArr;

            $hierarchy_arr_rev = array_reverse($hierarchy_arr);

            //prd($hierarchy_arr_rev);

            if(!empty($hierarchy_arr_rev) && count($hierarchy_arr_rev) > 0){
                foreach($hierarchy_arr_rev as $cat){

                    $cat = (object)$cat;

                    if(isset($cat->name)){
                        if(!empty($first_uri_name)){
                            $breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                        }

                        $breadcrumb .= '<a href="'.url($first_uri.'&parent_id='.$cat->id).'" class="btn-link" >'.$cat->name.'</a>';
                        $breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                    }
                }
                //$breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
            }
            elseif(!empty($first_uri_name)){
                $breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
            }
            if($is_last_link){
                $breadcrumb .= '<a href="'.url('admin/categories?parent_id='.$category->id.'&back_url='.$BackUrl).'">'.$category->name.'</a>';
            }
            else{
                $breadcrumb .= '<a href="javascript:void(0)">'.$category->name.'</a>';
            }            
            
        }

        return $breadcrumb;
    }


    public static function CategoryBreadcrumbFrontend($category, $first_uri, $first_uri_name, $is_last_link = false){

        Self::$parentCatArr = [];

        //prd($category->toArray());
        $breadcrumb = '';

        if(!empty($first_uri_name)){
            $breadcrumb .= '<a href="'.url($first_uri).'" >'.$first_uri_name.'</a>';
        }

        $hierarchy_arr = [];

        if(!empty($category) && count($category) > 0){

            $category_id = (isset($category->pivot->id))?$category->pivot->id:0;
            $p1_cat = (isset($category->pivot->p1_cat))?$category->pivot->p1_cat:0;
            $p2_cat = (isset($category->pivot->p2_cat))?$category->pivot->p2_cat:0;

            Self::categoryParentForBreadcrumb($category);

            $hierarchy_arr = Self::$parentCatArr;

            $hierarchy_arr_rev = array_reverse($hierarchy_arr);

            //prd($hierarchy_arr_rev);

            $pcat = '';

            if(!empty($hierarchy_arr_rev) && count($hierarchy_arr_rev) > 0){

                foreach($hierarchy_arr_rev as $cat){

                    $cat = (object)$cat;

                    if(isset($cat->name)){
                        if(!empty($first_uri_name)){
                            $breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                        }

                        $pCatUrl = route('products.list', ['pcat'=>$cat->slug]);

                        if($cat->id == $p1_cat){
                            $pcat = $cat->slug;
                            $pCatUrl = route('products.list', ['pcat'=>$cat->slug]);
                        }
                        elseif($cat->id == $p2_cat){
                            //$pCatUrl = 'javascript:void(0)';
                            $pCatUrl = route('products.list', ['pcat'=>$pcat,'p2cat'=>$cat->slug]);
                        }

                        $breadcrumb .= '<a href="'.$pCatUrl.'" >'.$cat->name.'</a>';
                        $breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
                    }
                }
                //$breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
            }
            elseif(!empty($first_uri_name)){
                $breadcrumb .= '&nbsp;<i aria-hidden="true" class="fa fa-angle-double-right"></i>&nbsp;';
            }

            if($is_last_link){

                $catUrl = route('products.list', ['pcat'=>$pcat,'cat[]'=>$category->slug]);

                $breadcrumb .= '<a href="'.$catUrl.'">'.$category->name.'</a>';
            }
            else{
                //$breadcrumb .= '<a href="javascript:void(0)">'.$category->name.'</a>';
                $breadcrumb .= $category->name;
            }
            
        }

        return $breadcrumb;
    }



    public static function categoryDropDown($dropdown_name, $classAttr='', $idAtrr='', $selected_value='', $allow_multiple=false){

        $dropdown = '<select name="'.$dropdown_name.'" class="'.$classAttr.'" id="'.$idAtrr.'" >';

        if($allow_multiple){

            $dropdown = '<select name="'.$dropdown_name.'[]" class="'.$classAttr.'" id="'.$idAtrr.'" multiple>';
        }

        $dropdown .= '<option value="">--Select--</option>';

        $categories = Category::where(['parent_id'=>0])->orderBy('name')->get();

        if(!empty($categories) && count($categories) > 0){
            foreach($categories as $category){
                $dropdown .= Self::makeCategoryDropDown($category, $selected_value);
            }
        }

        $dropdown .= '</select>';

        return $dropdown;
    }




    public static function makeCategoryDropDown($category, $selected_value=''){

        $selected = '';


        if(is_array($selected_value))
        {
            if(in_array($category->id,$selected_value))
            {
                $selected = 'selected';
            }

        }
        else
        {
            if($category->id == $selected_value)
            {
                $selected = 'selected';
            }

        }



        $category_name = $category->name;

        if(isset($category->parent) && count($category->parent) > 0){
            $mark = Self::markCategoryParent($category);
            $category_name = $mark.$category_name;
        }

        $options = '<option value="'.$category->id.'" '.$selected.' >'.$category_name.'</option>';

        if(isset($category->children) && count($category->children) > 0){

            foreach($category->children as $child_cat){
                $options .= Self::makeCategoryDropDown($child_cat, $selected_value);
            }

        }
        return $options;
    }

    public static function markCategoryParent($category){
        $mark = '';

        if(isset($category->parent) && count($category->parent) > 0){
            $mark .= ' - ';
            $category_parent = $category->parent;
            $mark .= Self::markCategoryParent($category_parent);
        }

        return $mark;
    }


    public static function getMenu($slug='top-menu', $parent=0){
        $result = '';
        if(!empty($slug)){
            $result = Menu::where(['slug'=>$slug, 'status'=>1])->first();
        }

        return $result;
    }


    public static function getMenuItemsList($menuItems, $menu_id, $is_parent = true, $parent_class='', $child_class=''){

        $routeName = Self::getAdminRouteName();

        $list = '';
        if($is_parent){
            $list .= '<ol class="'.$parent_class.'">';
        }

        if(!empty($menuItems) && count($menuItems) > 0){

            foreach($menuItems as $mi){

                $list .= '<li class="'.$child_class.'" id="item_id_'.$mi->id.'">';

                $list .= $mi->title;

                $item_url = route($routeName.'.menus.items', $menu_id.'/'.$mi->id);

                $list .= '&nbsp;&nbsp;<a href="'.$item_url.'" title="Edit"><i class="fas fa-edit"></i></a>';
                $list .= '&nbsp;&nbsp;<a href="javascript:void(0)" data-id="'.$mi->id.'" class="delItem" title="Delete"><i class="fas fa-trash-alt"></i></a>';

                if(isset($mi->children) && count($mi->children) > 0){
                    $list .= '<ol class="">';
                    $list .= Self::getMenuItemsList($mi->children, $menu_id, false, $parent_class, $child_class);
                    $list .= '</ol>';
                }
                $list .= '</li>';
            }
        }

        if($is_parent){
            $list .= '</ol>';
        }


        return $list;
    }


    public static function getMenuForFront($menuItems, $is_parent = true, $parent_class='', $child_class='', $child_parent_class=''){

        $routeName = Self::getAdminRouteName();

        $list = '';
        if($is_parent){
            $list .= '<ul class="'.$parent_class.'">';
        }

        if(!empty($menuItems) && count($menuItems) > 0){

            foreach($menuItems as $mi){

                $menuUrl = url($mi->url);

                $target = $mi->target;

                if($mi->link_type=='external' &&  !empty($mi->url)){
                    $menuUrl = $mi->url;
                }

                $list .= '<li class="'.$child_class.'" id="item_id_'.$mi->id.'">';

                $list .= '<a href="'.$menuUrl.'" target="'.$target.'">';
                $list .= $mi->title;
                $list .= '</a>';

                if(isset($mi->children) && count($mi->children) > 0){
                    $list .= '<ul class="'.$child_parent_class.'">';
                    $list .= Self::getMenuForFront($mi->children, false, $parent_class, $child_class);
                    $list .= '</ul>';
                }
                
                $list .= '</li>';
            }
        }

        if($is_parent){
            $list .= '</ul>';
        }


        return $list;
    }


    public static function getNameFromNumber($num){

        $index = 0;
        $index = abs($index * 1);
        $numeric = ($num - $index) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - $index) / 26);
        if ($num2 > 0) {
            return Self::getNameFromNumber(
                $num2 - 1 + $index
            ) . $letter;
        } else {
            return $letter;
        }
    }

    public static function BackUrl(){
        $uri = request()->path();
        if (count(request()->input()) > 0){
            $request_input = request()->input();
            if(isset($request_input['back_url'])){
                unset($request_input['back_url']);
            }
            $uri .= '?' . http_build_query($request_input, '', "&");
        }
        //rawurlencode(str)
        //return rawurlencode($uri);
        return $uri;
    }

    public static function sendEmail($viewPath, $viewData, $to, $from, $replyTo, $subject, $params=array()){

        try{

            Mail::send(
                $viewPath,
                $viewData,
                function($message) use ($to, $from, $replyTo, $subject, $params) {
                    $attachment = (isset($params['attachment']))?$params['attachment']:'';

                    if(!empty($replyTo)){
                        $message->replyTo($replyTo);
                    }
                    
                    if(!empty($from)){
                        $message->from($from,env('APP_NAME'));
                    }

                    if(!empty($attachment)){
                        $message->attach($attachment);
                    }

                    $message->to($to);
                    $message->subject($subject);

                }
            );
        }
        catch(\Exception $e){
            // Never reached
        }

        if( count(Mail::failures()) > 0 ) {
            return false;
        }       
        else {
            return true;
        }

    }

    public static function sendEmailRaw($html, $plainText, $to, $from, $replyTo, $subject, $params=array()){

        try{

            Mail::raw(
                [],
                function($message) use ($html, $plainText, $to, $from, $replyTo, $subject, $params) {
                    $attachment = (isset($params['attachment']))?$params['attachment']:'';

                    if(!empty($replyTo)){
                        $message->replyTo($replyTo);
                    }

                    if(!empty($from)){
                        $message->from($from);
                    }

                    if(!empty($attachment)){
                        $message->attach($attachment);
                    }

                    $message->setBody($html,'text/html');
                    $message->addPart($plainText,'text/plain');
                    
                    $message->to($to);
                    $message->subject($subject);

                }
            );
        }
        catch(\Exception $e){
            // Never reached
        }

        if( count(Mail::failures()) > 0 ) {
            return false;
        }       
        else {
            return true;
        }
    }
    

    public static function getCMSPage($name, $cols=array('*')){

        //prd($name);

        $data = [];

        $data['title'] = '';
        $data['heading'] = '';
        $data['content'] = '';
        $data['meta_title'] = '';
        $data['meta_keyword'] = '';
        $data['meta_description'] = '';
        $data['description'] = '';


        if(!empty($name)){
            $cms_data = CmsPages::where('slug', $name)->select($cols)->first();
            if(!empty($cms_data)){

                $title = (isset($cms_data->title))?$cms_data->title:'';
                $id = (isset($cms_data->id))?$cms_data->id:'';
                $parent_id = (isset($cms_data->parent_id))?$cms_data->parent_id:'';
                $heading = (isset($cms_data->heading))?$cms_data->heading:'';
                $brief = (isset($cms_data->brief))?$cms_data->brief:'';
                $content = (isset($cms_data->content))?$cms_data->content:'';
                $template = (isset($cms_data->template))?$cms_data->template:'';
                $image = (isset($cms_data->image))?$cms_data->image:'';
                $banner_image = (isset($cms_data->banner_image))?$cms_data->banner_image:'';

                $meta_title = (isset($cms_data->meta_title))?$cms_data->meta_title:'';
                $meta_keyword = (isset($cms_data->meta_keyword))?$cms_data->meta_keyword:'';
                $meta_description = (isset($cms_data->meta_description))?$cms_data->meta_description:'';
                $description = (isset($cms_data->description))?$cms_data->description:'';


                $data['title'] = $title;
                $data['id'] = $id;
                $data['parent_id'] = $parent_id;
                $data['heading'] = $heading;
                $data['brief'] = $brief;
                $data['template'] = $template;
                $data['image'] = $image;
                $data['banner_image'] = $banner_image;
                $data['content'] = $content;
                $data['meta_title'] = $meta_title;
                $data['meta_keyword'] = $meta_keyword;
                $data['meta_description'] = $meta_description;
                $data['cms'] = $cms_data;
                $data['description'] = $description;
                
            }
        }

        return $data;
    }

    public static function updateData($tbl, $id_col, $id, $data){

        $is_updated = 0;

        if(!empty($tbl) && !empty($id_col) && is_numeric($id) && $id > 0 && is_array($data) && count($data) > 0){
            $is_updated = DB::table($tbl)->where($id_col, $id)->update($data);
        }

        return $is_updated;
    }


    public static function isSerialized($value, &$result = null){

        if(empty($value)){
            return false;
        }

    // Bit of a give away this one
        if (!is_string($value))
        {
            return false;
        }
    // Serialized false, return true. unserialize() returns false on an
    // invalid string or it could return false if the string is serialized
    // false, eliminate that possibility.
        if ($value === 'b:0;')
        {
            $result = false;
            return true;
        }
        $length = strlen($value);
        $end    = '';
        switch ($value[0])
        {
            case 's':
            if ($value[$length - 2] !== '"')
            {
                return false;
            }
            case 'b':
            case 'i':
            case 'd':
            // This looks odd but it is quicker than isset()ing
            $end .= ';';
            case 'a':
            case 'O':
            $end .= '}';
            if ($value[1] !== ':')
            {
                return false;
            }
            switch ($value[2])
            {
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                break;
                default:
                return false;
            }
            case 'N':
            $end .= ';';
            if ($value[$length - 1] !== $end[0])
            {
                return false;
            }
            break;
            default:
            return false;
        }
        if (($result = @unserialize($value)) === false)
        {
            $result = null;
            return false;
        }
        return true;
    }


    public static function makeStarRatingArr($rating=5){

        $ratingArr = explode('.', $rating);

        $count = $ratingArr[0];

        $revCount = 5 - $count;

        $starColorArr = [];

        for($i=1; $i<=$count; $i++){
            $starColorArr[] = '<span class="fa fa-star color"></span>';
        }

        $starArr = [];

        if($revCount > 0){
            for($r=1; $r<=$revCount; $r++){
                $starArr[] = '<span class="fa fa-star"></span>';
            }
        }

        $starArray = array_merge($starColorArr, $starArr);

        return $starArray;

    }


    public static function addUserWallet($user_id, $by_user_id, $credit_amount, $debit_amount, $params=''){

        $user = User::where('id', $user_id)->select(['id', 'wallet_bal'])->first();

        $wallet_bal = $user->wallet_bal;

        if(empty($wallet_bal)){
            $wallet_bal = self::updateUserWalletBal($user_id, 0);
        }

        $new_balance = $wallet_bal - $debit_amount;

        $description = (isset($params['description']))?$params['description']:'';
        $orderNumber = (isset($params['orderNumber']))?$params['orderNumber']:0;

        $wallet_data['user_id'] = $user_id;
        $wallet_data['by_user_id'] = $by_user_id;
        $wallet_data['credit_amount'] = $credit_amount;
        $wallet_data['debit_amount'] = $debit_amount;
        $wallet_data['balance'] = $new_balance;
        $wallet_data['description'] = $description;
        $wallet_data['created'] = date('Y-m-d H:i:s');
        $wallet_data['updated'] = $wallet_data['created'];

        $is_saved = UserWallet::create($wallet_data);

        if($is_saved){
            self::updateUserWalletBal($user_id, $new_balance);

            if(!empty($orderNumber)){
                Order::where('order_number', $orderNumber)->update(['wallet_used'=>1,'wallet_amount'=>$debit_amount]);
            }
        }

    }


    public static function updateUserWalletBal($user_id, $balance){
        if(is_numeric($user_id) && $user_id){
            if(is_numeric($balance) && $balance > 0){

                $update_data['wallet_bal'] = $balance;
                $update_data['updated_at'] = date('Y-m-d H:i:s');

                $is_updated = User::where('id', $user_id)->update($update_data);
            }
            else{
                $UsersWalletQuery = UserWallet::where('user_id', $user_id);

                $count_user_wallet = $UsersWalletQuery->count();

                if($count_user_wallet > 0){

                    $user_wallet = $UsersWalletQuery->get();

                    $credit_total = 0;
                    $debit_total = 0;
                    foreach($user_wallet as $uw){
                        $credit_total = $credit_total + $uw->credit_amount;
                        $debit_total = $debit_total + $uw->debit_amount;
                    }
                    $balance = $credit_total - $debit_total;

                    $update_data['wallet_bal'] = $balance;
                    $update_data['updated_at'] = date('Y-m-d H:i:s');

                    $is_updated = User::where('id', $user_id)->update($update_data);
                }
            }
        }
        return $balance;
    }

    public static function checkUserWalletBal($user_id, $amount){

        $result = false;

        if(is_numeric($user_id) && $user_id && is_numeric($amount) && $amount > 0){
            $user = User::where('id', $user_id)->select(['id', 'wallet_bal'])->first();

            //prd($user);

            $wallet_bal = $user->wallet_bal;

            if(empty($wallet_bal) || $wallet_bal == 0.00){
                $UsersWalletQuery = UserWallet::where('user_id', $user_id);

                $count_user_wallet = $UsersWalletQuery->count();

                if($count_user_wallet > 0){

                    $user_wallet = $UsersWalletQuery->get();

                    $credit_total = 0;
                    $debit_total = 0;
                    foreach($user_wallet as $uw){
                        $credit_total = $credit_total + $uw->credit_amount;
                        $debit_total = $debit_total + $uw->debit_amount;
                    }
                    $wallet_bal = $credit_total - $debit_total;

                    $update_data['wallet_bal'] = $wallet_bal;
                    $update_data['updated_at'] = date('Y-m-d H:i:s');

                    $is_updated = User::where('id', $user_id)->update($update_data);

                    if($wallet_bal >= $amount){
                        $result = true;
                    }
                }
            }
            elseif($wallet_bal >= $amount){
                $result = true;
            }
        }
        return $result;
    }

    public static function calculateUserWalletBal($user_id){
        $wallet_bal = 0;

        if(is_numeric($user_id) && $user_id > 0){

            $UsersWalletQuery = UserWallet::where('user_id', $user_id);

            $count_user_wallet = $UsersWalletQuery->count();

            if($count_user_wallet > 0){

                $user_wallet = $UsersWalletQuery->get();

                $credit_total = 0;
                $debit_total = 0;
                foreach($user_wallet as $uw){
                    $credit_total = $credit_total + $uw->credit_amount;
                    $debit_total = $debit_total + $uw->debit_amount;
                }
                $wallet_bal = $credit_total - $debit_total;

                $update_data['wallet_bal'] = $wallet_bal;
                $update_data['updated_at'] = date('Y-m-d H:i:s');

                $is_updated = User::where('id', $user_id)->update($update_data);

            }
        }

        return $wallet_bal;
    }


    public static function wordsLimit($str, $limit = 150, $isStripTags=false, $allowTags=''){
        $newStr = '';
        if(strlen($str) <= $limit){
            $newStr = $str;
        }
        else{
            $newStr = substr($str, 0, $limit).' ...';
        }

        if($isStripTags){
            if(!empty($allowTags)){
                $newStr = strip_tags($newStr, $allowTags);
            }
            else{
                $newStr = strip_tags($newStr);
            }
        }

        return $newStr;
    }

    public static function convertCurrency($amount, $from='INR', $to='USD', $decimals=0){
        $CurrencyConverter = new CurrencyConverter();

        $converted = $CurrencyConverter->convert($amount, $from, $to, $decimals);

        return $converted;
    }

    public static function isMobile($userAgent = null, $httpHeaders = null){
        $detect = new MobileDetect;

        $detected = $detect->isMobile($userAgent = null, $httpHeaders = null);

        return $detected;
    }
    

    private static $categoryAttributes = [];

    public static function getParentCategoryAttributes($category){

        if(!empty($category) && count($category) > 0){

            if(isset($category->parent) && count($category->parent) > 0){
                Self::getParentCategoryAttributes($category->parent);
            }

            $attributes = (isset($category->categoryAttributes))?$category->categoryAttributes:'';
            if(!empty($attributes) && count($attributes) > 0){
                Self::$categoryAttributes[] = $attributes;
            }
        }

        return Self::$categoryAttributes;
    }



    public static function getData($tbl, $id=0, $where='', $selectArr=['*'], $params=[]){

        $result = '';

        $orderByArr = (isset($params['orderBy']))?$params['orderBy']:'';
        $featured = (isset($params['featured']))?$params['featured']:'0';

        $query = DB::table($tbl);

        $query->select($selectArr);

        if(!empty($where) && count($where) > 0){
            $query->where($where);
        }

        if(!empty($orderByArr) && count($orderByArr) > 0){
            foreach($orderByArr as $orderKey=>$orderVal){
                $query->orderBy($orderKey, $orderVal);
            }
        }

        if(isset($featured) && !empty($featured)){
            $query->where('featured',$featured);
        }

        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $query->limit($params['limit']);
        }

        if(is_numeric($id) && $id > 0){
            $query->where('id', $id);
            $result = $query->first();
        }
        else{
            $result = $query->get();
        }
        
        return $result;
    }

    public static function calculateProductDiscount($mainPrice, $salePrice){

        $discount = 0;

        if(!empty($mainPrice) && !empty($salePrice)){ 
            $discount = (($mainPrice - $salePrice)/$mainPrice)*100;
        }
        
        return $discount;
    }

    public static function calculateProductShipping($weight, $qty){

        $shippingCharge = 0;

        if(is_numeric($weight) && $weight > 0 && is_numeric($qty) && $qty > 0){

        }
        
        return $shippingCharge;
    }


    // Common Function for GetEvents
    public static function getEvents($id='', $limit='', $params=[]){
        $events = '';

        //$params['orderBy'] = ['id'=>'asc', 'name'=>'desc'];

        $orderByArr = (isset($params['orderBy']))?$params['orderBy']:'';

        $featured = (isset($params['featured']))?$params['featured']:'0';

        $event_query = Event::where('status', 1);

        if(!empty($orderByArr) && count($orderByArr) > 0){
            foreach($orderByArr as $orderKey=>$orderVal){
                $event_query->orderBy($orderKey, $orderVal);
            }
        }

        if(isset($featured) && !empty($featured)){
            $event_query->where('featured',$featured);
        }

        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $event_query->limit($params['limit']);
        }

        if(is_numeric($id) && $id > 0){
            $event_query->where('id', $id);
            $events = $event_query->first();
        }
        else{
            $events = $event_query->get();
        }

        return $events;
    }



    public static function getBlogs1($id='', $type='blogs', $limit='', $params=[]){
        $data = '';

        $blog_type_arr = config('custom.blog_type_arr');

        $type = (isset($blog_type_arr[$type]))?$type:'blogs';

        //$params['orderBy'] = ['id'=>'asc', 'name'=>'desc'];

        $orderByArr = (isset($params['orderBy']))?$params['orderBy']:'';

        $featured = (isset($params['featured']))?$params['featured']:'0';

        $news_query = Blog::where(['status'=>1, 'type'=>$type]);

        if(!empty($orderByArr) && count($orderByArr) > 0){
            foreach($orderByArr as $orderKey=>$orderVal){
                $news_query->orderBy($orderKey, $orderVal);
            }
        }

        if(isset($featured) && !empty($featured)){
            $news_query->where('featured',$featured);
        }

        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $news_query->limit($params['limit']);
        }

        if(is_numeric($id) && $id > 0){
            $news_query->where('id', $id);
            $data = $news_query->first();
        }
        else{
            $data = $news_query->get();
        }

        return $data;
    }



    public static function getProducts($id='', $limit='', $params=[]){
        $data = '';

        //$params['orderBy'] = ['id'=>'asc', 'name'=>'desc'];

        $orderByArr = (isset($params['orderBy']))?$params['orderBy']:'';

        $featured = (isset($params['featured']))?$params['featured']:'0';

        $query = Product::where(['status'=>1]);

        if(!empty($orderByArr) && count($orderByArr) > 0){
            foreach($orderByArr as $orderKey=>$orderVal){
                $query->orderBy($orderKey, $orderVal);
            }
        }

        if(isset($featured) && !empty($featured)){
            $query->where('featured',$featured);
        }

        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $query->limit($params['limit']);
        }

        if(is_numeric($id) && $id > 0){
            $query->where('id', $id);
            $data = $query->first();
        }
        else{
            $data = $query->get();
        }

        return $data;
    }


    public static function convert_number_to_words($number) {

        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . Self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . Self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }


    public static function getCmsData($id=0, $parent_id=0, $limit='', $params=[]){
        $data = '';

        $parent_id = (isset($parent_id))?$parent_id:0;

        $featured = (isset($params['featured']))?$params['featured']:'0';

        $query = CmsPages::where('status',1);

        if(is_numeric($parent_id) && $parent_id > 0){
            $query->where('parent_id',$parent_id);
        }

        if(isset($featured) && !empty($featured)){
            $query->where('featured',$featured);
        }

        if(isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0){
            $query->limit($params['limit']);
        }

        if(is_numeric($id) && $id > 0){
            $query->where('id', $id);
            $data = $query->first();
        }
        else{
            $data = $query->get();
        }

        return $data;
    }


    /* End Common Function */


    // For Menus

    public static function getHeaderMenu($menu_name='', $menu_id='', $parent_id=0, $ul_class="", $child_ul_class='',$li_class='',$child_li_class=''){

        $mess = ''; 
        $id = 0;
        if($menu_name != ''){
            $menu = Menu::where(['slug'=>$menu_name])->first();
            
            if(!empty($menu)){
                $id = $menu->id;
            }
        }
        $query = MenuItem::where('menu_id',$id)->orderBy('sort_order','asc');

        if(!empty($menu_id)){
            $query->where('id', $menu_id);
        }

        if(!empty($parent_id)){
            $query->where('parent_id', $parent_id);
        }

        $itemData = $query->get();
        $res = $itemData;
        //prd($itemData);

        $total_menu = count($itemData); 
        $menu_count = 2;

        if(!empty($res)){

            $mess.='<ul class="'.$ul_class.'">';
            foreach($res as $r){

                $url=''; 
                if(!empty($r->slug_url)) {
                    $url = url($r->slug_url);
                }

                if(empty($url)){
                    $url='javascript:void(0)'; 

                    if($r->url=='/'){
                        $url = url('');
                    }

                    if($r->link_type=='external' &&  !empty($r->url)){
                        $url = $r->url; 
                    }

                    if($r->link_type=='internal' && !empty($r->url)){
                        $url =url('/').$r->url;
                    }
                }

                $name = $r->title;
                $target = $r->target;

                $mess.= '<li class='.$li_class.'><a target='.$target.' href='.$url.'>'.$name.'</a>';
                $mess.= self::getHeaderMenu($menu_name='',$r->menu_id,$r->id, $child_ul_class,'',$child_li_class,'');
                $mess.= '</li>'; 

                if($r->parent_id==0) {
                    $menu_count++;
                }
            }
            $mess.= '</ul>';
        }
        return $mess; 
    }

    public static function getStaticFormElements(){
        $result = [];

        $formElements = FormElement::where('is_static', 1)->get();

        return $formElements;
    }


    function randomNumberOrder($qtd){
        $Caracteres = '0123456789'; 
        $QuantidadeCaracteres = strlen($Caracteres); 
        $QuantidadeCaracteres--; 

        $ransom_num=NULL; 
        for($x=1;$x<=$qtd;$x++){ 
            $Posicao = rand(0,$QuantidadeCaracteres); 
            $ransom_num .= substr($Caracteres,$Posicao,1); 
        } 

        return $ransom_num;
    }





    public static function send_notification($title, $body, $deviceToken,$image){
        $sendData = array(
            'body' => !empty($body) ? $body : '',
            'title' => !empty($title) ? $title : '',
            'image' => !empty($image) ? $image : '',
            'sound' => 'Default'
        );

        return self::fcmNotification($deviceToken,$sendData);
    }

    public static function fcmNotification($device_id, $sendData)
    {
        #API access key from Google API's Console
        if (!defined('API_ACCESS_KEY')){
            define('API_ACCESS_KEY', 'AAAAvWysmTI:APA91bErvKHn3cCH4vmzzdyG_GNPui2T9ub5rIyn0QcPTQwvOZoMIyVQkPael9Ep9SN1dwBwgpOblq6U0ad5dpp-4ADqPOkDuiWhxZ9TxVLIlISmc0xRwM9d3hllK9Qp4C7QyGf2AYh7');
        }


        $fields = array
        (
            'to'    => $device_id,
            'data'  => $sendData,
            'notification'  => $sendData,
        // "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch);
        if($result === false){
            die('Curl failed ' . curl_error($ch));
        }
    // prd($result);
        curl_close($ch);
        return true;
    }





    public static function sendAllUsers($notify){
        UserLogin::latest('id')
        ->chunk(50, function($users) use ($notify) {
            foreach ($users as $user) {
                $deviceToken = $user->deviceToken;
                if(!empty($deviceToken)){
                    $title = $notify->title;
                    $body = $notify->text;
                    $imageUrl ='';
                    if(!empty($image)){
                        $imageUrl = url('public/storage/notification/'.$notify->image);
                    }
                    $success = self::send_notification($title, $body, $deviceToken,$imageUrl);
                    if($success){
                     $dbArray = [];
                     $dbArray['user_id'] = $user->user_id;
                     $dbArray['text'] = $notify->text??'';
                     $dbArray['title'] = $notify->title ?? '';
                     $dbArray['image'] = $imageUrl;
                     DB::table('notifications')->insert($dbArray);
                     return back()->with('alert-success', 'Notification Sent Successfully');  
                 }
             }
         }
     });

        DB::table('schedule_notification')->where('id',$notify->id)->update(['is_send'=>1]);

    }

    public static function sendCategoryBusinessUsers($notify){
        $business = DB::table('business_cat_subcat')->where('cat_id',$notify->category_id)->groupBy('business_id')->pluck('business_id')->toArray();
        Business::latest('id')->whereIn('id',$business)->where('is_delete',0)
        ->chunk(50, function($business) use ($notify) {
            foreach ($business as $user) {
                $devices = UserLogin::where('user_id',$user->parent)->first();
                $deviceToken = $devices->deviceToken??'';
                if(!empty($deviceToken)){
                    $title = $notify->title;
                    $body = $notify->text;
                    $imageUrl ='';
                    if(!empty($image)){
                        $imageUrl = url('public/storage/notification/'.$notify->image);
                    }
                    $success = self::send_notification($title, $body, $deviceToken,$imageUrl);
                    if($success){
                     $dbArray = [];
                     $dbArray['user_id'] = $user->parent;
                     $dbArray['text'] = $notify->text??'';
                     $dbArray['title'] = $notify->title ?? '';
                     $dbArray['image'] = $imageUrl;
                     DB::table('notifications')->insert($dbArray);
                     return back()->with('alert-success', 'Notification Sent Successfully');  
                 }
             }
         }
     });

        DB::table('schedule_notification')->where('id',$notify->id)->update(['is_send'=>1]);
    }


    public static function sendAllBusinessUsers($notify){

        Business::latest('id')->where('is_delete',0)
        ->chunk(50, function($business) use ($notify) {
            foreach ($business as $user) {
                $devices = UserLogin::where('user_id',$user->parent)->first();
                $deviceToken = $devices->deviceToken??'';
                if(!empty($deviceToken)){
                    $title = $notify->title;
                    $body = $notify->text;
                    $imageUrl ='';
                    if(!empty($image)){
                        $imageUrl = url('public/storage/notification/'.$notify->image);
                    }
                    $success = self::send_notification($title, $body, $deviceToken,$imageUrl);
                    if($success){
                     $dbArray = [];
                     $dbArray['user_id'] = $user->parent;
                     $dbArray['text'] = $notify->text??'';
                     $dbArray['title'] = $notify->title ?? '';
                     $dbArray['image'] = $imageUrl;
                     DB::table('notifications')->insert($dbArray);
                     return back()->with('alert-success', 'Notification Sent Successfully');  
                 }
             }
         }
     });

        DB::table('schedule_notification')->where('id',$notify->id)->update(['is_send'=>1]);
    }


    public static function sendSingleUsers($request,$image){

       $user_logins = UserLogin::where('user_id',$request->user_id)->get();
       if(!empty($user_logins)){
        foreach ($user_logins as $key) {
            $deviceToken = $key->deviceToken;
            if(!empty($deviceToken)){
                $title = $request->title1;
                $body = $request->text1;
                $imageUrl ='';
                if(!empty($image)){
                    $imageUrl = url('public/storage/notification/'.$image);
                }
                $success = self::send_notification($title, $body, $deviceToken,$imageUrl);
                if($success){
                 $dbArray = [];
                 $dbArray['user_id'] = $key->user_id;
                 $dbArray['text'] = $request->text1??'';
                 $dbArray['title'] = $request->title1 ?? '';
                 $dbArray['image'] = $imageUrl;
                 DB::table('notifications')->insert($dbArray);
                 return back()->with('alert-success', 'Notification Sent Successfully');  
             }
         }else{
          return back()->with('alert-danger', 'No Device Found');  
      }

  }
}else{
    return back()->with('alert-danger', 'No Login Found');  
}
return back()->with('alert-success', 'Notification Sent Successfully');  



}



public static function sendCourseSubscriptionUsers($request,$image){

    $user = SubscriptionHistory::select('user_id')->where('course_id',$request->course_id)->where('end_date','>=',date('Y-m-d'))->where('paid_status',1)->pluck('user_id')->toArray();

    $user_logins = UserLogin::whereIn('user_id',$user)->get();
    if(!empty($user_logins)){
        foreach ($user_logins as $key) {
            $deviceToken = $key->deviceToken;
            if(!empty($deviceToken)){
                $title = $title;
                $body = $text;
                $imageUrl ='';
                if(!empty($image)){
                    $imageUrl = url('public/storage/notification/'.$image);
                }
                $success = $this->send_notification($title, $body, $deviceToken,$imageUrl);
                if($success){
                   $dbArray = [];
                   $dbArray['user_id'] = $key->user_id;
                   $dbArray['text'] = $request->text??'';
                   $dbArray['title'] = $request->title ?? '';
                   $dbArray['image'] = $imageUrl;
                   DB::table('notifications')->insert($dbArray);
               }
           }

       }
   }




}


public static function sendBirthDayEmail(){
    $users = User::select('id','name','email','phone','dob')->where('dob',date('Y-m-d'))->where('is_delete',0)->get();
    if(!empty($users)){
        foreach($users as $user){
            $to_email = $user->email;
            $subject = 'BirthDay Wish - '.env('APP_NAME');
            $ADMIN_EMAIL = config('custom.admin_email');
            $from_email = $ADMIN_EMAIL;
            $email_data = [];
            $email_data['name'] = $user->name;
            $email_data['phone'] = $user->phone;
            $email_data['dob'] = $user->dob;
            $is_send = self::sendEmail('emails.birthday_email', $email_data, $to=$to_email, $from_email, $replyTo = $from_email, $subject);

        }
    }

}









/* End of helper class */
}