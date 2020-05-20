<?php
/**
 * 用户收货地址模型
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberAddress extends Model
{
    protected $table='member_address';
    protected $guarded = ['id'];
    protected $fillable = ['province','user_id','city','district','name','address','phone'];

}
