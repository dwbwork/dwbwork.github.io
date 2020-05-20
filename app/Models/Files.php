<?php
// +----------------------------------------------------------------------
// | 文件模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
   

    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id','tmp','type','filename','size','url','name','oss_type','ext'
    ];

    //所属文件组
    
    public function group()
    {
        return $this->belongsTo('App\Models\Item\FileGroup','group_id','id')->withDefault(['name'=>'无分组']);
    }

}
