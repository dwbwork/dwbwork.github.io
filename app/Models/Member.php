<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table='member';

    protected $fillable = ['openid','key','api_token','email','name','nick_name','phone','avatar_url','integral','create_time'];
    
    protected $hidden = ['unionid','api_token','key'];

    
}
