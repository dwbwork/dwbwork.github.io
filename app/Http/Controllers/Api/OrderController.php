<?php

namespace App\Http\Controllers\Api;
/*
 * 订单控制器
 * */

use App\Models\Item\Cart;
use App\Models\Item\OrderList;
use App\Models\MemberAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\Item\Order;
use App\Http\Controllers\Home\WechatPay;
use App\Http\Controllers\Home\AliPay;
use QrCode;
use App\Models\Item\ItemSpec;
use App\Models\Item\ItemCategory;
use App\Models\Item\ItemSku;
use App\Models\Item\Item;
use App\Models\Item\ItemSkuSpec;
use App\Models\Item\ItemSpecValue;
use Illuminate\Support\Facades\Cookie;
class OrderController extends Controller
{

    /**提交订单【立即购买】
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_order(Request $request){

          $data = $request->all();
          $prefix = date('Ymd');
          //检查
       try{

          DB::beginTransaction();
          //获取地址
          $address = MemberAddress::findOrFail($data['address_id']);
          $data['address'] = $address->province.$address->city.$address->district.$address->address;
          $data['phone'] = $address->phone;
          $data['user_id'] = $request->user_id;
          //普通课程
              if(!Item::where(['id'=>$data['goods_id'],'goods_status'=>1])->exists()){
                 throw new \Exception('商品不存在或已下架');
              }
              $info = Item::findOrFail($data['goods_id']);


             //$data['master_order_sn'] = $prefix.random_int(100000, 999999).substr(microtime(true),-4);
             $data['order_sn'] = $prefix.random_int(100000, 999999).substr(microtime(true),-4);

           if($data['pay_type'] == 1){//微信支付

               $data['pay_type'] = 1;
               /*  $wechat_pay = new WechatPay;
                 $wechat_pay->index($data['order_sn'],$info->price);*/


           }else{//支付b宝支付

               $data['pay_type'] = 3;
               /*  $wechat_pay = new WechatPay;
                 $wechat_pay->index($data['order_sn'],$info->price);*/


           }
           $info->spec_type == 1 && Item::where('id',$data['goods_id'])->decrement('inventory',1);
           $info->spec_type == 2 && ItemSku::where('id',$data['sku_id'])->decrement('inventory',1);
           //增加销量
           Item::where('id',$data['goods_id'])->increment('sales_actual',1);


           $data['sku_name'] ='';
           if($info->spec_type == 2){//多规格
               $item_sku_info = ItemSku::findOrFail($data['sku_id']);
               foreach(explode('_',$item_sku_info->spec_sku_id) as $k=>$v){
                   $data['sku_name'].=ItemSpecValue::where('spec_value_id',$v)->value('spec_value').' ';
               }
               if($item_sku_info->inventory < $request->number) throw new \Exception('商品库存不足');
               $data['real_price'] = $item_sku_info->goods_price;
               $data['amount'] = $data['real_price'] * $data['number'];
           }else{//单规格
               $data['real_price'] = $info->goods_price;
               $data['amount'] = $info->goods_price * $data['number'];
               if($info->inventory < $request->number) throw new \Exception('商品库存不足');
           }
           $data['order_status'] = 1;
           $order_info = Order::create($data);
           $data['order_id'] = $order_info->id;
           $order_list_info = OrderList::create($data);


             DB::commit();
           return Response()->json(['code'=>200,'msg'=>'生成成功','data'=>['order_id'=>$order_info->id]]);
       }catch (\Exception $exception){
             DB::rollback();
           return Response()->json(['code'=>202,'msg'=>'生成失败','data'=>$exception->getMessage()]);
       }
    }

    /**提交订单【购物车购买】
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_cart_order(Request $request){

        $data = $request->all();
        $prefix = date('Ymd');
        //检查
        try{

            DB::beginTransaction();
            $data['user_id'] = $request->user_id;
            //获取地址
            $address = MemberAddress::findOrFail($data['address_id']);
            $data['address'] = $address->province.$address->city.$address->district.$address->address;
            $data['phone'] = $address->phone;
            //查询购物车
            $cart_ids = $request->cart_ids;

            //用于订单商品的数组
            $order_list=[];
            $data['amount']=0;
            foreach(explode(',',$cart_ids) as $k=>$v){

                $cart_info = Cart::findOrFail($v);
                //普通课程

                if(!Item::where(['id'=>$cart_info['goods_id'],'goods_status'=>1])->exists()){
                    throw new \Exception('商品不存在或已下架');
                }

                //增加销量
                Item::where('id',$cart_info['goods_id'])->increment('sales_actual',1);


                $order_list[$k]['sku_name'] ='';

                $data['amount'] = $cart_info['real_price'] * $cart_info['number'];

                $order_list[$k]['goods_id'] = $cart_info->goods_id;
                $order_list[$k]['real_price'] = $cart_info->real_price;
                $order_list[$k]['number'] = $cart_info->number;
                $order_list[$k]['sku_name'] = $cart_info->sku_name;

               //增加销量
                Item::where('id',$cart_info['goods_id'])->increment('sales_actual',$cart_info['number']);


                //清空购物车
                Cart::destroy($v);

            }
            //$data['master_order_sn'] = $prefix.random_int(100000, 999999).substr(microtime(true),-4);
            $data['order_sn'] = $prefix.random_int(100000, 999999).substr(microtime(true),-4);

            if($data['pay_type'] == 1){//微信支付

                $data['pay_type'] = 1;
                /*  $wechat_pay = new WechatPay;
                  $wechat_pay->index($data['order_sn'],$info->price);*/


            }else{//支付b宝支付

                $data['pay_type'] = 2;
                /*  $wechat_pay = new WechatPay;
                  $wechat_pay->index($data['order_sn'],$info->price);*/


            }

            $data['order_status'] = 1;
            //订单生成
            $order_info = Order::create($data);

            foreach($order_list as $k=>$v){
                $v['order_id'] = $order_info->id;

                $order_list_info = OrderList::create($v);
            }


            DB::commit();
            return Response()->json(['code'=>200,'msg'=>'生成成功','data'=>['order_id'=>$order_info->id]]);
        }catch (\Exception $exception){
            DB::rollback();
            return Response()->json(['code'=>202,'msg'=>'生成失败','data'=>$exception->getMessage()]);
        }
    }

    /**订单详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function order_detail(Request $request){
        $data = $request->all();
        $info = Order::with(['goods','nets','user_info'])->findOrFail($data['order_id']);

        return Response()->json(['code'=>200,'msg'=>'返回成功','data'=>['info'=>$info]]);

   }



    /**订单列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function order(Request $request){

        $res = Order::with(['order_list'=>function($query){
            $query->with(['goods:id,thumb'])->get();
        }])
            ->where(['user_id'=>$request->user_id,'order_status'=>$request->input('status',0)])
            ->orderBy('created_at','desc')
            ->paginate($request->input('limit',10))->toArray();


        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }

    //发布订单评价
    public function push_comment(Request $request){
        try{
            DB::beginTransaction();
            $date = date('Y-m-d H:i:s');
            $order = Order::findOrFail($request->order_id);
            OrderComment::create(['content'=>$request->content,'updated_at'=>$date,
                'created_at'=>$date,'store_id'=>$order->store_id,'user_id'=>$request->user_id,'images'=>@$request->images,
                'order_id'=>$request->order_id,'type'=>$order->type,'start'=>$request->start,'goods_id'=>$order->goods_id]);
            Order::where('id',$request->order_id)->update(['status'=>4]);

            //商家星级评选
            $start = OrderComment::where('store_id',$order->store_id)->avg('start');
           // Store::where('id',$order->store_id)->update(['start'=>$start]);
            DB::commit();
            return Response()->json(['code'=>200,'msg'=>'生成成功']);
        }catch(\Exception $exception){
            DB::rollBack();
            return Response()->json(['code'=>400,'msg'=>'生成失败','data'=>$exception->getMessage()]);
        }
    }

    /**
     * 取消订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel_order(Request $request){
        try{
            DB::beginTransaction();

            $order_info = Order::findOrFail($request->order_id);
            if($order_info->order_status !=0) throw new \Exception('订单状态异常');

            //取消订单
            $order_info ->order_status = 5;
            $order_info->update();

            //商品回滚
            $order_list = OrderList::where('order_id',$request->order_id)->get()->toArray();
            foreach($order_list as $k=>$v){
                !is_null($v['sku_id']) && ItemSku::where('id',$v['sku_id'])->increment('inventory',$v['number']);
                 is_null($v['sku_id']) && Item::where('id',$v['goods_id'])->increment('inventory',$v['number']);
                Item::where('id',$v['goods_id'])->decrement('sales_actual',$v['number']);
            }

            DB::commit();
            return Response()->json(['code'=>200,'msg'=>'取消成功']);
        }catch(\Exception $exception){
            DB::rollBack();
            return Response()->json(['code'=>400,'msg'=>'取消失败','data'=>$exception->getMessage()]);
        }
    }


    /*我的评价*/
    public function my_comment(Request $request){
        $model = OrderComment::query();

        $res = $model->where(['user_id'=>$request->user_id])->with(['wx_user','goods','nets'])->orderBy('created_at','desc')
            ->paginate($request->input('limit',10))->toArray();
        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }

    /*删除评价*/
    public function del_comment(Request $request){
        $model = OrderComment::query();

        $res = $model->where(['user_id'=>$request->user_id,'id'=>$request->id])->delete();
        return Response()->json(['code'=>200,'msg'=>'删除成功']);
    }
}
