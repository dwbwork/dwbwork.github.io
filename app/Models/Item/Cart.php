<?php
// +----------------------------------------------------------------------
// | 购物车模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'cart';
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id','goods_id','sku_id','real_price','sku_name','number'

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
