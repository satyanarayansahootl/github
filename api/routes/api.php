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
            Route::post('/register', [PassportController::class, 'register']);
            Route::match(['get','post'],'/login', [PassportController::class, 'login'])->name('login');
            Route::match(['get','post'],'/cms_pages', [PassportController::class, 'cms_pages'])->name('cms_pages');
      
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/profile', [PassportController::class, 'profile']);
            Route::post('/update_profile', [PassportController::class, 'update_profile']);
            Route::post('/home', [PassportController::class, 'home']);
            Route::post('/apply_loan', [PassportController::class, 'apply_loan']);

            
            Route::post('/logout', [PassportController::class, 'logout']);

           


        });
    
});
