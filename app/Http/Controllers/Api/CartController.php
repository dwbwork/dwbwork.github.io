<?php
// +----------------------------------------------------------------------
// | 购物车接口控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item\Cart;
use App\Models\Item\Item;
use App\Models\Item\ItemSpec;
use App\Models\Item\ItemCategory;
use App\Models\Item\ItemSku;
use App\Models\Item\ItemSkuSpec;
use App\Models\Item\ItemSpecValue;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * 添加购物车
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request){
        try{

            $data = $request->except('key');
            $data['user_id'] = $request->user_id;
            //检查商品数量和状态

            $item_info = Item::findOrFail($request->goods_id);
            if($item_info->goods_status == 0) throw new \Exception('商品已下架');

            //多规格
            if($item_info->spec_type = 2 ){
                $item_sku_info = ItemSku::findOrFail($request->sku_id);

                //判断库存
                if($request->number > $item_sku_info->inventory) throw new \Exception('库存不足');

                $data['sku_name']='';
                foreach(explode('_',$item_sku_info->spec_sku_id) as $k=>$v){
                       $data['sku_name'].=ItemSpecValue::where('spec_value_id',$v)->value('spec_value').' ';
                }
                $data['real_price'] = $item_sku_info->goods_price;
                //减去库存
                ItemSku::where('id',$request->sku_id)->decrement('inventory',$request->number);

            }else{
            //单规格
                //判断库存
                if($request->number > $item_info->inventory) throw new \Exception('库存不足');

                $data['real_price'] = $item_info->goods_price;
                //减去库存
                Item::where('id',$request->goods_id)->decrement('inventory',$request->number);
            }

            //如果存在购物车则累加,不存在则新增
            if(Cart:: where(['goods_id'=>$request->goods_id,'user_id'=>$request->user_id,'sku_id'=>$request->sku_id])->exists()) {
                Cart::where(['goods_id'=>$request->goods_id,'user_id'=>$request->user_id,'sku_id'=>$request->sku_id])
                    ->increment('number',$request->number);
            }else{

                Cart::create($data);
            }

            return Response()->json(['code'=>200,'msg'=>'添加成功']);
        }catch(\Exception $e){
            return Response()->json(['code'=>400,'msg'=>$e->getMessage()]);
        }
    }

    /**
     * 购物车列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cart_list(Request $request){

        $res = Cart::with('goods','item_sku')->where('user_id',$request->user_id)
            ->orderBy('created_at','desc')
            ->paginate($request->input('limit',10))
            ->toArray();
        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }

    /**
     * 删除购物车
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request){

        try{
            DB::beginTransaction();
            //批量删除
            $data = $request->all();
            foreach(explode(',',$data['ids']) as $k=>$v){
                $cart_info = Cart::findOrFail($v);
                !is_null($cart_info->sku_id) && ItemSku::where('id',$cart_info->sku_id)->increment('inventory',$cart_info->number);
                is_null($cart_info->sku_id) && Item::where('id',$cart_info->goods_id)->increment('inventory',$cart_info->number);
                Cart::destroy($v);
            }

            DB::commit();
            return Response()->json(['code'=>200,'msg'=>'删除成功']);
        }catch(\Exception $e){
            DB::rollBack();
            return Response()->json(['code'=>400,'msg'=>'删除失败']);
        }


    }
}
