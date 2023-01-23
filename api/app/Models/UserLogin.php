<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    
    protected $table = 'user_logins';

    protected $guarded = ['id'];
}
