<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponseHelpers;
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function sendOtp(Request $request) : JsonResponse
    {

        $validator =  Validator::make($request->all(), [
           'mobile_number' => 'required|digits:10'
       ]);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'otp'=> '',
                'message' => json_encode($validator->errors()),

            ],200);
        }

        $mobile_number = $request->input('mobile_number');
        $user = User::where(['mobile_number' => $mobile_number])->first();
        if (empty($user)) {
            $user = User::create([
                'mobile_number' => $mobile_number,
                'auth_medium' => 1
            ]);
        }
        $otp = 9999;
        $user->otp = $otp;
        $user->otp_expiration = Carbon::now()->addMinutes(10);
        $user->save();

        $data = [
            'result' => true,
            'message' => 'Otp sent',
            'is_new' => empty($user->name)
        ];
        return $this->respondWithSuccess($data);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits:10',
            'otp' => 'required|numeric'
        ]);

        $mobile_number = $request->input('mobile_number');
        $otp = $request->input('otp');

        $user = User::where(['mobile_number' => $mobile_number, 'otp' => $otp])->first();
        if (empty($user)) {
            return $this->respondError('Incorrect otp');
        }

        $data = [
            'success' => true,
            'message' => 'Valid otp',
            'is_new' => empty($user->name)
        ];
        return $this->respondWithSuccess($data);
    }

    public function logout(Request $request) : JsonResponse
    {
        $user = $request->user();
        $user->token()->revoke();
        $data = [
            'success' => true,
            'message' => 'You have successfully logged out'
        ];
        return $this->respondWithSuccess($data);
    }

    public function profile(Request $request) : JsonResponse
    {
        $user = $request->user();
        $userResponse = [
            "id" => $user->id,
            "name" => $user->name,
            "profile_picture" => $user->profile_picture ? Storage::disk('user')->url($user->profile_picture) : null,
            "email" => $user->email,
            "mobile_number" => $user->mobile_number,
            "city" => $user->city,
            "dob" => $user->dob,
            "exam" => $user->exam,
            "active" => $user->active,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at 
        ];
        $data = [
            'success' => true,
            'message' => '',
            'data' => $userResponse
        ];
        return $this->respondWithSuccess($data);
    }

    public function updateProfile(Request $request) : JsonResponse
    {
        $user = $request->user();
        $request->validate([
            'mobile_number' => ['digits:10', Rule::unique('users')->ignore($user->id)],
            'email' => ['email', Rule::unique('users')->ignore($user->id)],
            'dob' => ['date', 'nullable']
        ]);
        
        $user->update($request->all());

        $userResponse = [
            "id" => $user->id,
            "name" => $user->name,
            "profile_picture" => $user->profile_picture ? Storage::disk('user')->url($user->profile_picture) : null,
            "email" => $user->email,
            "mobile_number" => $user->mobile_number,
            "city" => $user->city,
            "dob" => $user->dob,
            "exam" => $user->exam,
            "active" => $user->active,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at 
        ];
        $data = [
            'success' => true,
            'message' => '',
            'data' => $userResponse
        ];
        return $this->respondWithSuccess($data);
    }
}
