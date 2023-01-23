<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LaravelEntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'profile_picture',
        'email',
        'email_verified_at',
        'password',
        'mobile_number',
        'otp',
        'otp_expiration',
        'city',
        'dob',
        'auth_medium',
        'exam',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return User
     */
    public function findForPassport($username)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return $this->where('email', $username)->first();
        } else {
            return $this->where('mobile_number', $username)->first();
        }
    }

    // Owerride password for passport
    public function validateForPassportPasswordGrant($password)
    {
        if (empty($password)) {
            return false;
        }
        
        if(Hash::check($password, $this->password)){
            return true;
        }

        if($password == $this->otp){
            return true;
        }
        return false;
    }
}
