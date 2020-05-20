<?php
// +----------------------------------------------------------------------
// | 订单商品模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class OrderList extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'order_list';
    protected $guarded = ['id'];
    protected $fillable = [
    'order_id','goods_id','sku_id','real_price','sku_name','old_price','number'
        
    ];

    //关联商品
    public function goods(){
        return $this->belongsTo('App\Models\Item\Item','goods_id','id')->withDefault(['name'=>'无商品']);
    }
    //关联规格
    public function item_sku(){
        return $this->belongsTo('App\Models\Item\ItemSku','sku_id','id')->withDefault(['name'=>'无商品规格']);
    }
}
