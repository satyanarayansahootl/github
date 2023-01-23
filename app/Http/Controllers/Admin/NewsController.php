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
use App\Models\News;
use App\Roles;
use App\Models\ExamCategory;
use Storage;
use DB;
use Hash;



Class NewsController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     $data =[];
     $news = News::where('is_delete',0)->orderBy('id','desc');

     $news = $news->paginate(10);
     $data['news'] = $news;

     return view('admin.news.index',$data);
 }



 public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;

    $news = '';
    if(!empty($id)){
        $news = News::where('id',$id)->first();
        if(empty($news)){
            return redirect($this->ADMIN_ROUTE_NAME.'/news');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/news';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];
        

        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'News has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'News has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add News';

    if(!empty($id)){
        $exam_categories_name = $news->title;
        $page_heading = 'Update News - '.$exam_categories_name;
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['news'] = $news;

    $subcategories = [];

     if(!empty($id)){
        $subcategories = SubCategory::where('category_id',$news->category_id)->get();
     }

    $data['subcategories'] = $subcategories;

    return view('admin.news.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password']);
    
    if($id == 0){
        $slug = CustomHelper::GetSlug('news', 'id', '', $request->title);
        $data['slug'] = $slug;
    }
    $oldImg = '';

    $news = new News;

    if(!empty($id)){
        $exist = News::where('id',$id)->first();

        if(isset($exist->id) && $exist->id == $id){
            $news = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $news->$key = $val;
    }

    $isSaved = $news->save();

    if($isSaved){
        $this->saveImage($request, $news, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $news, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'news/';
        $thumb_path = 'news/thumb/';
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
            $news->image = $image;
            $news->save();         
        }

        if(!empty($uploaded_data)){   
            return $uploaded_data;
        }  

    }

}




public function delete(Request $request){
    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = News::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'News has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_new_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $data = News::where('id',$id)->first();
  if(!empty($data)){

     News::where('id',$id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'Not  Found';
     return response()->json($response);  
 }

}


private function saveImageMultiple($request,$society_id){

    $files = $request->file('file');
    $path = 'societydocument/';
    $storage = Storage::disk('public');
            //prd($storage);
    $IMG_WIDTH = 768;
    $IMG_HEIGHT = 768;
    $THUMB_WIDTH = 336;
    $THUMB_HEIGHT = 336;
    $dbArray = [];

    if (!empty($files)) {

        foreach($files as $file){
            $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
            if($uploaded_data['success']){
                $image = $uploaded_data['file_name'];
                $dbArray['file'] = $image;
                $dbArray['society_id'] = $society_id;

                $success = SocietyDocument::create($dbArray);
            }
        }
        return true;
    }else{
        return false;
    }
}

public function assign_types(Request $request){
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['course_id'] = 'required';
        $rules['type_ids'] = 'required';
        $this->validate($request,$rules);

        $type_ids = $request->type_ids??'';
        if(!empty($type_ids)){
            CourseType::where('course_id',$request->course_id)->delete();
            foreach($type_ids as $type_id =>$value){
                $dbArray = [];
                $dbArray['course_id'] = $request->course_id??'';
                $dbArray['type_id'] = $value;
                CourseType::insert($dbArray);
            }
        }
        return back()->with('alert-success', 'Course Type Assign successfully.');

    }
}


}