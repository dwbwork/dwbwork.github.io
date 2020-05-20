<?php
// +----------------------------------------------------------------------
// | 订单模型
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mysql';
    protected  $table = 'order';
    protected $guarded = ['id'];
    protected $fillable = [
    'user_id','order_sn','master_order_sn','order_type','order_status','phone','address','amount','pay_type'
        
    ];

    //关联订单商品
    public function order_list(){
        return $this->hasMany('App\Models\Item\OrderList','order_id','id');
    }
}
