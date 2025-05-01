<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'email_verification_code',
        'is_email_verified',
        'role',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
