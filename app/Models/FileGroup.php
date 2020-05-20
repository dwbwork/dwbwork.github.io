<?php
// +----------------------------------------------------------------------
// | 文件分组模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileGroup extends Model
{
   

    protected $table = 'file_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','model_type'
    ];

    //所属文件组
    
    public function files()
    {
        return $this->hasMany('App\Models\Item\FileGroup','group_id','id')->withDefault(['name'=>'无文件']);
    }

}
