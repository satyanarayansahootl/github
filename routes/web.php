<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login', function () {
//     //return view('welcome');
// })->name('login');



// Admin
Route::group(['namespace' => 'App\Http\Controllers'], function() {

    ///////////////////////// WEBSITE ROUTES /////////////////////////////////////////////////////


    // Route::match(['get','post'],'/', 'HomeController@index')->name('index');
    Route::match(['get','post'],'/save_tab_keys', 'HomeController@save_tab_keys')->name('save_tab_keys');


    Route::match(['get','post'],'/send_otp', 'HomeController@send_otp')->name('send_otp');
    

    Route::match(['get','post'],'/update_exam_category', 'HomeController@update_exam_category')->name('home.update_exam_category');



    Route::match(['get','post'],'/courses/{type_name?}/{type?}', 'HomeController@courses')->name('courses');
    Route::match(['get','post'],'/course-details/{slug}', 'HomeController@course_details')->name('course_details');
    Route::match(['get','post'],'/about-us', 'HomeController@about')->name('about-us');
    Route::match(['get','post'],'/contact-us', 'HomeController@contact')->name('contact');

    Route::match(['get','post'],'/blogs', 'HomeController@blogs')->name('blogs');
    Route::match(['get','post'],'/current-affairs', 'HomeController@current_affairs')->name('current_affairs');












    ///////////////////////// ADMIN ROUTES /////////////////////////////////////////////////////


    Route::match(['get', 'post'], 'admin/login', 'Admin\LoginController@index')->name('admin.login');
    Route::match(['get', 'post'], 'get_city', 'Admin\HomeController@get_city')->name('get_city');
    Route::match(['get', 'post'], 'get_state', 'Admin\HomeController@get_state')->name('get_state');
    Route::match(['get', 'post'], 'admin/logout', 'Admin\LoginController@logout');

    $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    
    Route::group(['namespace' => 'Admin', 'prefix' => $ADMIN_ROUTE_NAME, 'as' => $ADMIN_ROUTE_NAME.'.', 'middleware' => ['authadmin']], function() {

        Route::match(['get','post'],'/',  'HomeController@index')->name('home');
        Route::match(['get','post'],'/profile',  'HomeController@profile')->name('profile');
        Route::match(['get','post'],'/change_password',  'HomeController@change_password')->name('change_password');
        Route::match(['get','post'],'/set_tab_in_session',  'HomeController@set_tab_in_session')->name('set_tab_in_session');

        
        Route::match(['get','post'],'/get_sub_category',  'HomeController@get_sub_category')->name('get_sub_category');
        Route::match(['get','post'],'/get_subjects',  'HomeController@get_subjects')->name('get_subjects');
        Route::match(['get','post'],'/get_chapter',  'HomeController@get_chapter')->name('get_chapter');



        ////admins
        Route::group(['prefix' => 'admins', 'as' => 'admins' , 'middleware' => ['allowedmodule:admins,list'] ], function() {

            Route::get('/', 'AdminController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'AdminController@add')->name('.add');

            Route::match(['get', 'post'], 'get_admins', 'AdminController@get_admins')->name('.get_admins');
            Route::match(['get', 'post'], 'change_admins_status', 'AdminController@change_admins_status')->name('.change_admins_status');
            Route::match(['get', 'post'], 'change_admins_approve', 'AdminController@change_admins_approve')->name('.change_admins_approve');
            Route::match(['get', 'post'], 'edit', 'AdminController@add')->name('.edit');
            Route::post('ajax_delete_image', 'AdminController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'AdminController@delete')->name('.delete');
            Route::match(['get','post'],'change_admins_role', 'AdminController@change_admins_role')->name('.change_admins_role');

        });
        ////exam_categories
        Route::group(['prefix' => 'exam_categories', 'as' => 'exam_categories' , 'middleware' => ['allowedmodule:exam_categories,list'] ], function() {

            Route::match(['get', 'post'],'/' ,'ExamCategoryController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'ExamCategoryController@add')->name('.add');

            Route::match(['get', 'post'], 'change_exam_catgories_status', 'ExamCategoryController@change_exam_catgories_status')->name('.change_exam_catgories_status');
            Route::match(['get', 'post'], 'edit/{id}', 'ExamCategoryController@add')->name('.edit');
            Route::post('ajax_delete_image', 'ExamCategoryController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'ExamCategoryController@delete')->name('.delete');

        }); 

           ////subcategories
        Route::group(['prefix' => 'subcategories', 'as' => 'subcategories' , 'middleware' => ['allowedmodule:subcategories,list'] ], function() {

            Route::match(['get', 'post'],'/', 'SubCategoryController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'SubCategoryController@add')->name('.add');

            Route::match(['get', 'post'], 'change_subcategories_status', 'SubCategoryController@change_subcategories_status')->name('.change_subcategories_status');
            Route::match(['get', 'post'], 'edit/{id}', 'SubCategoryController@add')->name('.edit');
            Route::post('ajax_delete_image', 'SubCategoryController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'SubCategoryController@delete')->name('.delete');

        }); 

           ////types
        Route::group(['prefix' => 'types', 'as' => 'types' , 'middleware' => ['allowedmodule:types,list'] ], function() {

            Route::get('/', 'TypeController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'TypeController@add')->name('.add');

            Route::match(['get', 'post'], 'change_type_status', 'TypeController@change_type_status')->name('.change_type_status');
            Route::match(['get', 'post'], 'edit/{id}', 'TypeController@add')->name('.edit');
            Route::post('ajax_delete_image', 'TypeController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'TypeController@delete')->name('.delete');

        });



         ////courses
        Route::group(['prefix' => 'courses', 'as' => 'courses' , 'middleware' => ['allowedmodule:courses,list'] ], function() {

            Route::match(['get', 'post'],'/', 'CourseController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'CourseController@add')->name('.add');

            Route::match(['get', 'post'], 'change_course_status', 'CourseController@change_course_status')->name('.change_course_status');
            Route::match(['get', 'post'], 'assign_types', 'CourseController@assign_types')->name('.assign_types');
            Route::match(['get', 'post'], 'edit/{id}', 'CourseController@add')->name('.edit');
            Route::post('ajax_delete_image', 'CourseController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'CourseController@delete')->name('.delete');
            Route::match(['get','post'],'contents/{id}', 'CourseController@contents')->name('.contents');
            Route::match(['get','post'],'upload_content', 'CourseController@upload_content')->name('.upload_content');
            Route::match(['get','post'],'update_live_class', 'CourseController@update_live_class')->name('.update_live_class');
            Route::match(['get','post'],'update_notes', 'CourseController@update_notes')->name('.update_notes');
            Route::match(['get','post'],'update_video', 'CourseController@update_video')->name('.update_video');
            Route::match(['get','post'],'add_coupon', 'CourseController@add_coupon')->name('.add_coupon');
            Route::match(['get','post'],'faqs', 'CourseController@faqs')->name('.faqs');
            Route::match(['get','post'],'update_rating', 'CourseController@update_rating')->name('.update_rating');
            
        });


////subjects
        Route::group(['prefix' => 'subjects', 'as' => 'subjects' , 'middleware' => ['allowedmodule:subjects,list'] ], function() {

            Route::match(['get', 'post'],'/', 'SubjectController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'SubjectController@add')->name('.add');

            Route::match(['get', 'post'], 'change_subject_status', 'SubjectController@change_subject_status')->name('.change_subject_status');          
            Route::match(['get', 'post'], 'edit/{id}', 'SubjectController@add')->name('.edit');            
            Route::match(['get','post'],'delete/{id}', 'SubjectController@delete')->name('.delete');
       

        });

        ////chapters
        Route::group(['prefix' => 'chapters', 'as' => 'chapters' , 'middleware' => ['allowedmodule:chapters,list'] ], function() {

            Route::match(['get', 'post'],'/', 'ChapterController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'ChapterController@add')->name('.add');

            Route::match(['get', 'post'], 'change_chapter_status', 'ChapterController@change_chapter_status')->name('.change_chapter_status');          
            Route::match(['get', 'post'], 'edit/{id}', 'ChapterController@add')->name('.edit');            
            Route::match(['get','post'],'delete/{id}', 'ChapterController@delete')->name('.delete');
       

        });
   ////contents
        Route::group(['prefix' => 'contents', 'as' => 'contents' , 'middleware' => ['allowedmodule:contents,list'] ], function() {

            Route::match(['get', 'post'],'/', 'ContentController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'ContentController@add')->name('.add');

            Route::match(['get', 'post'], 'change_contents_status', 'ContentController@change_contents_status')->name('.change_contents_status');
            Route::match(['get', 'post'], 'assign_types', 'ContentController@assign_types')->name('.assign_types');
            Route::match(['get', 'post'], 'edit/{id}', 'ContentController@add')->name('.edit');
            Route::post('ajax_delete_image', 'ContentController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'ContentController@delete')->name('.delete');

        });




         ////news
        Route::group(['prefix' => 'news', 'as' => 'news' , 'middleware' => ['allowedmodule:news,list'] ], function() {

            Route::match(['get', 'post'],'/', 'NewsController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'NewsController@add')->name('.add');

            Route::match(['get', 'post'], 'change_new_status', 'NewsController@change_new_status')->name('.change_new_status');
            Route::match(['get', 'post'], 'assign_types', 'NewsController@assign_types')->name('.assign_types');
            Route::match(['get', 'post'], 'edit/{id}', 'NewsController@add')->name('.edit');
            Route::post('ajax_delete_image', 'NewsController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'NewsController@delete')->name('.delete');

        });


        

        Route::group(['prefix' => 'banners' , 'as' => 'banners', 'middleware' => ['allowedmodule:banners,list'] ],  function() {
            Route::match(['get','post'],'/', 'BannerController@index')->name('.index');
            Route::match(['get','post'], 'add', 'BannerController@add')->name('.add');

            Route::match(['get','post'], 'edit/{id}', 'BannerController@add')->name('.edit');

            Route::match(['get','post'], 'delete/{id}', 'BannerController@delete')->name('.delete');
            Route::match(['get','post'], 'change_banner_status', 'BannerController@change_banner_status')->name('.change_banner_status');

        });
///////////////////////////////////////exams////////////////////////////////
        Route::group(['prefix' => 'exams', 'as' => 'exams', 'middleware' => ['allowedmodule:exams,list']],  function () {
            Route::match(['get', 'post'], '/', 'ExamController@index')->name('.index');
             Route::match(['get', 'post'], 'add', 'ExamController@add')->name('.add');
            Route::match(['get', 'post'], 'edit/{id}', 'ExamController@add')->name('.edit');
            Route::match(['get', 'post'], 'change_exam_status', 'ExamController@change_exam_status')->name('.change_exam_status');
            Route::match(['get', 'post'], 'delete/{id}', 'ExamController@delete')->name('.delete');

            

            Route::match(['get', 'post'], 'import/{id}', 'ExamController@import')->name('.import');
            Route::match(['get', 'post'], 'add_question/{exam_id}', 'ExamController@add_question')->name('.add_question');
            Route::match(['get', 'post'], 'get_exam_question', 'ExamController@get_exam_question')->name('.get_exam_question');
            Route::match(['get', 'post'], 'edit_question{question_id}', 'ExamController@edit_question')->name('.edit_question');
            Route::match(['get', 'post'], 'change_status', 'ExamController@change_status')->name('.change_status');
            Route::match(['get', 'post'], 'get_course', 'ExamController@get_course')->name('.get_course');
            Route::match(['get', 'post'], 'get_topic', 'ExamController@get_topic')->name('.get_topic');
           
            Route::post('ajax_delete_image', 'ExamController@ajax_delete_image')->name('.ajax_delete_image');
            
            Route::match(['get', 'post'], 'results/{exam_id}', 'ExamController@results')->name('.results');
            Route::match(['get', 'post'], 'get_result_list{exam_id}', 'ExamController@get_result_list')->name('.get_result_list');
            Route::match(['get', 'post'], 'allocate_exam', 'ExamController@allocate_exam')->name('.allocate_exam');
        });

        
/////// USERS

        Route::group(['prefix' => 'user' , 'as' => 'user', 'middleware' => ['allowedmodule:user,list'] ],  function() {
            Route::match(['get','post'],'/', 'UserController@index')->name('.index');
            Route::match(['get','post'], 'add', 'UserController@add')->name('.add');

            Route::match(['get','post'], 'edit/{id}', 'UserController@add')->name('.edit');
            Route::match(['get','post'], 'subscriptions/{id}', 'UserController@subscriptions')->name('.subscriptions');

            Route::match(['get','post'], 'delete_subscription/{id}', 'UserController@delete_subscription')->name('.delete_subscription');
            Route::match(['get','post'], 'update_subscription', 'UserController@update_subscription')->name('.update_subscription');

            Route::match(['get','post'], 'wallet', 'UserController@wallet')->name('.wallet');
            
            Route::match(['get','post'], 'export', 'UserController@export')->name('.export');

            Route::match(['get','post'], 'delete/{id}', 'UserController@delete')->name('.delete');
            Route::match(['get','post'], 'subscription', 'UserController@subscription')->name('.subscription');
            Route::match(['get','post'], 'change_users_status', 'UserController@change_users_status')->name('.change_users_status');
            Route::match(['get','post'], 'profile/{id}', 'UserController@profile')->name('.profile');

            Route::match(['get','post'], 'wallet_update', 'UserController@wallet_update')->name('.wallet_update');
            Route::match(['get','post'], 'update_profile', 'UserController@update_profile')->name('.update_profile');
            Route::match(['get','post'], 'free_subscription', 'UserController@free_subscription')->name('.free_subscription');
            Route::match(['get','post'], 'update_subs_enddate', 'UserController@update_subs_enddate')->name('.update_subs_enddate');


        });
        /////// loan_requests

        Route::group(['prefix' => 'loan_requests' , 'as' => 'loan_requests', 'middleware' => ['allowedmodule:loan_requests,list'] ],  function() {
            Route::match(['get','post'],'/', 'LoanRequestController@index')->name('.index');
            Route::match(['get','post'], 'add', 'LoanRequestController@add')->name('.add');

            Route::match(['get','post'], 'edit/{id}', 'LoanRequestController@add')->name('.edit');

            Route::match(['get','post'], 'delete/{id}', 'LoanRequestController@delete')->name('.delete');
            Route::match(['get','post'], 'change_loan_details_status', 'LoanRequestController@change_loan_details_status')->name('.change_loan_details_status');
            Route::match(['get','post'], 'loan_details/{id}', 'LoanRequestController@loan_details')->name('.loan_details');

          
        });


////////// COUNTRY

        Route::group(['prefix' => 'countries', 'as' => 'countries' , 'middleware' => ['allowedmodule:countries,list'] ], function() {

            Route::get('/', 'CountryController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'CountryController@add')->name('.add');
            Route::match(['get', 'post'], 'edit/{id}', 'CountryController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'CountryController@delete')->name('.delete');
        }); 


////////// STATE

        Route::group(['prefix' => 'states', 'as' => 'states' , 'middleware' => ['allowedmodule:states,list'] ], function() {

            Route::get('/', 'StateController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'StateController@add')->name('.add');
            Route::match(['get', 'post'], 'edit/{id}', 'StateController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'StateController@delete')->name('.delete');
        }); 


///////////// CITY
        Route::group(['prefix' => 'cities', 'as' => 'cities' , 'middleware' => ['allowedmodule:cities,list'] ], function() {

            Route::get('/', 'CityController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'CityController@add')->name('.add');
            Route::match(['get', 'post'], '/edit/{id?}', 'CityController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'CityController@delete')->name('.delete');
            Route::match(['get','post'], 'get_state', 'CityController@get_state')->name('.get_state');
            Route::match(['get','post'], 'get_city', 'CityController@get_city')->name('.get_city');
        });

        

// roles
        Route::group(['prefix' => 'roles', 'as' => 'roles' , 'middleware' => ['allowedmodule:roles,list'] ], function() {

            Route::get('/', 'RoleController@index')->name('.index');

            Route::match(['get', 'post'], 'add', 'RoleController@add')->name('.add');

            Route::match(['get', 'post'], 'get_roles', 'RoleController@get_roles')->name('.get_roles');

            Route::match(['get', 'post'], 'change_role_status', 'RoleController@change_role_status')->name('.change_role_status');
            Route::match(['get', 'post'], 'edit/{id}', 'RoleController@add')->name('.edit');

            Route::post('ajax_delete_image', 'RoleController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get','post'],'delete/{id}', 'RoleController@delete')->name('.delete');
        });  

// Permission
        Route::group(['prefix' => 'permission', 'as' => 'permission' , 'middleware' => ['allowedmodule:permission,list'] ], function() {

         Route::match(['get','post'],'/', 'PermissionController@index')->name('.index');
         Route::match(['get','post'],'/update_permission', 'PermissionController@update_permission')->name('.update_permission');
     });  




        Route::group(['prefix' => 'transactions' , 'as' => 'transactions', 'middleware' => ['allowedmodule:transactions,list'] ],  function() {
            Route::match(['get','post'],'/', 'TransactionController@index')->name('.index');
            Route::match(['get','post'], 'add', 'TransactionController@add')->name('.add');

            Route::match(['get','post'], 'edit/{id}', 'TransactionController@add')->name('.edit');

            Route::match(['get','post'], 'delete/{id}', 'TransactionController@delete')->name('.delete');
            Route::match(['get','post'], 'change_banner_status', 'TransactionController@change_banner_status')->name('.change_banner_status');

        });



        Route::group(['prefix' => 'subscription_history' , 'as' => 'subscription_history', 'middleware' => ['allowedmodule:subscription_history,list'] ],  function() {
            Route::match(['get','post'],'/', 'SubscriptionHistoryController@index')->name('.index');
            Route::match(['get','post'], 'add', 'SubscriptionHistoryController@add')->name('.add');

            Route::match(['get','post'], 'edit/{id}', 'SubscriptionHistoryController@add')->name('.edit');

            Route::match(['get','post'], 'delete/{id}', 'SubscriptionHistoryController@delete')->name('.delete');
            Route::match(['get','post'], 'change_banner_status', 'SubscriptionHistoryController@change_banner_status')->name('.change_banner_status');

        });





    });

});





