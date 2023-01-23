<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PassportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('v1/')->group(function () {
            Route::post('/send_otp', [PassportController::class, 'send_otp']);
            Route::post('/verify_otp', [PassportController::class, 'verify_otp']);
      

      
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/logout', 'UserController@logout');
            Route::get('/profile', 'UserController@profile');
            Route::post('/update-profile', 'UserController@updateProfile');
            Route::get('/exam-categories', 'ExamCategoryController@index');
        });
    
});
