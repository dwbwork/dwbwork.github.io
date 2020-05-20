<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $table = 'internet_plant';
    protected $guarded = ['id'];
    protected $fillable = ['plant_title','plant_num','classify_id','module_id','sort','created_at','updated_at'];

    public function category()
    {
        return $this->belongsTo('App\Models\Classify','classify_id','id')->withDefault(['category_title'=>'无分类']);
    }
//    public function module()
//    {
//        return $this->belongsTo('App\Models\module','module_id','id')->withDefault(['name'=>'无分类']);
//    }
}
