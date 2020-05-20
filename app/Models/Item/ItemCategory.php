<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
class ItemCategory extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'item_category';
    protected $guarded = ['id'];

    //子分类
    public function childs()
    {
        return $this->hasMany('App\Models\Item\ItemCategory','parent_id','id');
    }

    //所有子类
    public function allChilds()
    {
        return $this->childs()->with('allChilds');
    }
}
