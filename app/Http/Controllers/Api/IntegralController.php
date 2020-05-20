<?php

namespace App\Http\Controllers\Api;
/*
 * 
 * 积分商城控制器
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WxUsers;
use App\Models\IntegralItemSku;
use App\Models\Configuration;
use Illuminate\Support\Facades\Crypt;
use App\Models\IntegralItem;
use App\Models\IntegralDetail;
use App\Models\IntegralOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Redis;
class IntegralController extends Controller
{
/*
 * 
 * 积分商品列表
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function index(Request $request){
         $model = IntegralItem::query();
         if($request->input('name')){
         	$model = $model->where('title','like','%'.$request->get('name').'%');
         }
        // $info = WxUsers::findOrFail($request->user_id);
         $res = $model->where('status',1)->orderBy('created_at','desc')
            ->paginate($request->input('limit',10))->toArray();

        //$res['integral']=$info->integral;
         return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }

/**
 * Show the form for editing the specified resource.
 * 商品详情
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function item_detail(Request $request)
    {
        //验证用户信息
       /* $userInfo= WxUsers::where('openid',$request->only('openid'))->get();

        if(!$userInfo){
            return Response()->json(['code'=>202,'msg'=>'用户信息异常']);
        };*/

        $info = IntegralItem::with('sku')->findOrFail($request->only('id')['id']);
        

        if(!$info){
            return Response()->json(['code'=>202,'msg'=>'帖子不存在']);
        };
        $info->images= array_filter(explode(',', $info->image));
        $address = Configuration::findOrFail(21);


        return Response()->json(['code'=>200,'msg'=>'返回成功','data'=>['address'=>$address->val,'info'=>$info]]);

    }   
    

/*
 *
 * Show the form for editing the specified resource.
 * 提交订单
 * @param  int  $id
 * @return \Illuminate\Http\Response
*/
    public function create_order(Request $request)
    {
        $data = $request->only('goods_id','sku_id','number','amount');

         //验证用户信息
        $userInfo= WxUsers::findOrFail($request->user_id);
        $data['user_id'] = $request->user_id;
        if(!$userInfo){
            return Response()->json(['code'=>202,'msg'=>'用户信息异常']);
        };
  
          $prefix = date('Ymd');
          //检查
       try{
         DB::beginTransaction(); //开启事务
          
          if(!$data['sku_id'] || !IntegralItem::where('id',$data['goods_id'])->exists() || !IntegralItemSku::where('id',$data['sku_id'])->exists()){
              return Response()->json(['code'=>202,'msg'=>'此商品或规格不存在']);
          }

          //如果没有传规格id

             $info = IntegralItem::findOrFail($data['goods_id']);
             $sku_info = IntegralItemSku::findOrFail($data['sku_id']);
             $data['order_sn'] = $prefix.random_int(100000, 999999).substr(microtime(true),-4);
             $data['sku_name'] = $sku_info->attribute;
             $data['amount'] = $sku_info->attr_price;
             if($data['number']*$data['amount'] > $userInfo->integral){
                 return Response()->json(['code'=>202,'msg'=>'积分不足']);

             }

             //扣除库存
             IntegralItemSku::where('id',$data['sku_id'])->decrement('number',$data['number']);
             IntegralItem::where('id',$data['goods_id'])->decrement('inventory',$data['number']);
             //增加销量
             IntegralItem::where('id',$data['goods_id'])->increment('sale',$data['number']);
             //扣除积分
             WxUsers::where('id',$data['user_id'])->decrement('integral',$data['amount']);
             //生成积分明细
             IntegralDetail::create(['user_id'=>$request->user_id ,'integral'=>$data['amount'],'type'=>0]);
             IntegralOrder::create($data);
         DB::Commit();   
           return Response()->json(['code'=>200,'msg'=>'生成成功']);
       }catch (\Exception $exception){
         DB::Rollback(); 
           return Response()->json(['code'=>202,'msg'=>'生成失败','data'=>$exception->getMessage()]);
       }

    } 

    
/*
 *
 * Show the form for editing the specified resource.
 * 订单列表
 * @param  int  $id
 * @return \Illuminate\Http\Response
*/
    public function order(Request $request){
        $model = IntegralOrder::query();
         
         $res = $model->where(['user_id'=>$request->user_id,'status'=>$request->input('status',0)])->with('goods')->orderBy('created_at','desc')
            ->paginate($request->input('limit',10))->toArray();     
         return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }
}
