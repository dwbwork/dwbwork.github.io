<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class ItemSku extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'item_sku';
    protected $guarded = ['spec_id'];
    protected $fillable = [
    'goods_id','goods_price','line_price','inventory','spec_sku_id','updated_time','created_time'
        
    ];

    //规格列表
    public function spec_value()
    {
       return $this->belongsTo('App\Models\Item\Item','spec_id','spec_id')->withDefault(['name'=>'无规格类']);
    }
}
