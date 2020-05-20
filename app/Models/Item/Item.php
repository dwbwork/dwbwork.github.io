<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'item';
    protected $guarded = ['id'];
    protected $fillable = [
    'category_id','thumb','image','goods_price','goods_status','line_price','sales_initial',
        'inventory','goods_name','goods_no','spec_type','sort','content','sales_actual'
        
    ];

    //商品所属分类
    public function category()
    {
        return $this->belongsTo('App\Models\Item\ItemCategory','category_id','id')->withDefault(['name'=>'无分类']);
    }
    //商品下所有的sku
    public function ItemSku()
    {
        return $this->hasMany('App\Models\Item\ItemSku','goods_id','id');
    }
    //商品下所有的ItemSkuSpec
    public function ItemSkuSpec()
    {
        return $this->hasMany('App\Models\Item\ItemSkuSpec','goods_id','id');
    }
}
