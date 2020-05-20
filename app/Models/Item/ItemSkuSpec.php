<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class ItemSkuSpec extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'item_sku_spec';
    protected $guarded = ['id'];
    protected $fillable = [
    'goods_id','spec_value_id','spec_id','updated_time','created_time'
        
    ];

    //规格值
    public function ItemSpec()
    {
       return $this->belongsTo('App\Models\Item\ItemSpec','spec_id','spec_id')->withDefault(['name'=>'无规格']);
    }
    //规格值
    public function ItemSpecValue()
    {
       return $this->belongsTo('App\Models\Item\ItemSpecValue','spec_value_id','spec_value_id')->withDefault(['name'=>'无规格']);
    }
}
