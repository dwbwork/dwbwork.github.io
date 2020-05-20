<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 资讯列表模型
 * Class Journ
 * @package App\Models
 */
class Journ extends Model
{
    protected $connection = 'mysql';
    //指定数据库表名
    protected  $table = 'message_journ';
    protected $guarded = ['id'];
    protected $fillable = ['title','url_list','cate_id','content','sort_id','recom_type','status','created_at','updated_at'];

}
