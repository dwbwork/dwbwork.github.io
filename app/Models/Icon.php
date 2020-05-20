<?php
// +----------------------------------------------------------------------
// | 金刚区图标模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    protected $table = 'icon';
    protected $guarded = ['id'];
    protected $fillable = ['name','link','company_id','type','sort'];
    
   
   /**
    * 关联合作商
    */
   public function company(){
       return $this->belongsTo('App\Models\Company','company_id','id')->withDefault(['name'=>'无合作商']);
   }

}
