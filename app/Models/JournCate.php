<?php
/**
 * 资讯分类
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournCate extends Model
{
    protected $connection = 'mysql';
    //指定数据库表名
    protected  $table = 'journ_category';
    protected $guarded = ['id'];
    protected $fillable = ['name','company_id','sort','created_at','updated_at'];


}
