<?php
// +----------------------------------------------------------------------
// | 商品控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use App\Models\Item\Item;
use App\Models\Item\ItemSpec;
use App\Models\Item\ItemCategory;
use App\Models\Item\ItemSku;
use App\Models\Item\ItemSkuSpec;
use App\Models\Item\ItemSpecValue;
class ItemController extends BaseController
{
     /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Item();
    }

    public function getModel()
    {
        return new Item();
    }
    
    /**
     * 数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $model = Item::query();
        
        if ($request->get('category_id')){
           //获取所有子集

           $childs = $this->getAllNextId($request->get('category_id'));

           array_push($childs,(int)$request->get('category_id'));
            
            $model = $model->whereIn('category_id',$childs);
        }
        if ($request->get('goods_name')){
            $model = $model->where('goods_name','like','%'.$request->get('goods_name').'%');
        }
        if ($request->get('goods_no')){
            $model = $model->where('goods_no','like','%'.$request->get('goods_no').'%');
        }
        $request->goods_status && $model = $model->where('goods_status',$request->goods_status);
         
        $res = $model->with(['category'])->orderBy('id','desc')->paginate($request->get('limit',30));
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->total(),
            'data'  => $res->items(),
        ];
        return Response::json($data);
    }
    /**
     * 首页附带数据
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
         $categories = ItemCategory::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->get();
        
        return ['categories'=>$categories];
    }

   

    /**
     * 新增/编辑页附带数据
     *
     * @return \Illuminate\Http\Response
     */
    public function CreateEditData($id='')
    {
         $categories = ItemCategory::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->get();
        $sku = ItemSpec::with('spec_value')->get();
        return ['categories'=>$categories,'sku'=>$sku];
    }

    /**
     * 添加商品
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        try{
            DB::beginTransaction();
            $data=$request->all();
            if(is_null($data['content'])){
             return Redirect::back()->withErrors(['error'=>'内容不能为空']); 
        
            }
            if(!is_null($data['image'])) $data['image']=implode(',', @$data['image']);
            
            $goods = Item::create($data);
            if($request->spec_type ==1){//单规格
                
            }else{
                $skuData = $request->spec;//多规格数据
               
                foreach ($skuData as $key => $value) {
                    $skuData[$key]['goods_id']=$goods->id;
                   
                //spec  
                       
                foreach (explode('_',$value['spec_sku_id']) as $k => $v) {
                    $spec[$k]['goods_id'] = $goods->id;
                    $spec[$k]['spec_id'] = ItemSpecValue::where('spec_value_id',$v)->value('spec_id');
                    $spec[$k]['spec_value_id'] = $v;
                   
                     if(!ItemSkuSpec::where($spec[$k])->exists()) ItemSkuSpec::create($spec[$k]);
                }
                 
                 //sku添加
                   ItemSku::create($skuData[$key]); 
                }
                   
                
                

            }
            
            
           DB::commit();
           return Redirect::to(URL::route('admin.item'))->with(['success'=>'添加成功']);
           
        }catch (\Exception $exception){
              DB::rollBack();
              //return Response::json($exception->getMessage());
              return Redirect::back()->withErrors($exception->getMessage());
            
        }
    }

    /**
     * 更新资讯
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request,$id)
    {
        $info = Item::with(['ItemSku','ItemSkuSpec'=>function($query){
            $query->with('ItemSpec')->groupBy('spec_id')->get();
        }])->findOrFail($id)->toArray();
        $info['image'] = explode(',',$info['image']);
        
        
         //分类
        $categories = ItemCategory::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->get();
        //sku
        $sku = ItemSpec::with('spec_value')->get();
        //规格名
        if($info['spec_type'] ==2){
            foreach(explode('_', $info['item_sku'][0]['spec_sku_id']) as $k=>$v){
            $list[] = ItemSpecValue::where('spec_value_id',$v)->with('category')->get()->toArray();
        }
        
        
        //sku
        //$sku = ItemSpec::with('spec_value')->get();
        
        $spec = ItemSkuSpec::where('goods_id',$id)->pluck('spec_id')->toArray();

        $spec_value = ItemSkuSpec::where('goods_id',$id)->pluck('spec_value_id')->toArray();
        
        $item_spec_value = ItemSpecValue::all()->keyBy('spec_value_id')->toArray();
        
        
        //获取sku组合
        foreach ($info['item_sku'] as $key => $value) {
             
            foreach(explode('_', $value['spec_sku_id']) as $k=>$v){
                 
                 $info['item_sku'][$key]['spec'][$k] = $item_spec_value[$v]['spec_value'];
            }
        }
        //dd($info);
        //判断是否存在规格中
        foreach ($sku as $key => $value) {
            
            if(in_array($value->spec_id,$spec)){
                $value->active='sku-active';
            }else{
                $value->active=' ';
            }
            foreach ($value->spec_value as $k => $v) {
                if(in_array($v->spec_value_id,$spec_value)){
                $v->active='sku-active';
            }else{
                $v->active=' ';
            }
            }
        }
        return $this->display(compact('info','categories','sku','list'));
        
    } 
        return $this->display(compact('info','categories','sku'));
       
       
       
        
    }
    /**
     * 更新商品
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request ,$id)
    {
        $info =Item::findOrFail($id);

        $data = $request->all();
        try{
            DB::beginTransaction();
            $data=$request->all();
            if(is_null($request->content)){
             return Redirect::back()->withErrors(['error'=>'内容不能为空']); 
        
            }
            if(!is_null($data['image'])) $data['image']=implode(',', @$data['image']);
            
            $goods = $info->update($data);
            if($request->spec_type ==1){//单规格
                if(is_null($data['goods_price']) || is_null($data['line_price']) || is_null($data['inventory'])){
                    return Redirect::back()->withErrors(['error'=>'必填项不能为空']);
                }
                ItemSku::where('goods_id',$id)->delete();
                ItemSkuSpec::where('goods_id',$id)->delete();
            }else{
                $skuData = $request->spec;//多规格数据
                
                //删除之前存在的规格数据
                 //先删除不存在attr_id之中的sku
               
                ItemSku::where('goods_id',$id)->delete();
                ItemSkuSpec::where('goods_id',$id)->delete();
                foreach ($skuData as $key => $value) {
                    $skuData[$key]['goods_id']=$info->id;
                   
                //spec  
                       
                foreach (explode('_',$value['spec_sku_id']) as $k => $v) {
                    $spec[$k]['goods_id'] = $info->id;

                    $spec[$k]['spec_id'] = ItemSpecValue::where('spec_value_id',$v)->value('spec_id');
                    $spec[$k]['spec_value_id'] = $v;
                    
                     if(!ItemSkuSpec::where($spec[$k])->exists()) ItemSkuSpec::create($spec[$k]);
                }
                 
                 //sku添加
                   ItemSku::create($skuData[$key]); 
                }
                   
                
                

            }
            
            
           DB::commit();
           return Redirect::to(URL::route('admin.item'))->with(['success'=>'更新成功']);
           
        }catch (\Exception $exception){
              DB::rollBack();
              //return Response::json($exception->getMessage());
              return Redirect::back()->withErrors($exception->getMessage());
            
        }
    }

    /**
     * 删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        try{
            $this->model->destroy($ids);
            ItemSku::whereIn('goods_id',$ids)->delete();
            ItemSkuSpec::whereIn('goods_id',$ids)->delete();
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败']);
        }
    }

}
