<?php
// +----------------------------------------------------------------------
// | 规格值控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item\ItemSpecValue;
use App\Models\Item\ItemSkuSpec;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\BaseController;
class ItemSpecValueController extends BaseController
{
    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new ItemSpecValue();
    }

    public function getModel()
    {
        return new ItemSpecValue();
    }
    
    /**
     * 添加规格
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        try{
            DB::beginTransaction();
            
            
            if(ItemSpecValue::where(['spec_id'=>$request->spec_id,'spec_value'=>$request->spec_value])->exists()){
                throw new Exception('规格值已存在'); 
            }
            
            $specValue = ItemSpecValue::create($data);
           DB::commit();
           return Response::json(['code'=>200,'msg'=>'添加成功','data'=>['spec_value_id'=>$specValue->id]]);
        }catch (\Exception $exception){
              DB::rollBack();
            return Response::json(['code'=>1,'msg'=>'添加失败','data'=>$exception->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        $category = ItemSkuSpec::where('spec_value_id',$ids[0])->exists();
       
        if (!is_array($ids) || empty($ids)){

            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        DB::beginTransaction();
        try{
           if ($category){
            return Response::json(['code'=>1,'msg'=>'该规格下有商品，不能删除']);
          }
            ItemSpecValue::whereIn('spec_value_id',$ids)->delete();
            DB::commit();
            return Response::json(['code'=>200,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            DB::rollback();
            return Response::json(['code'=>1,'msg'=>'删除失败','data'=>$exception->getMessage()]);
        }
    }

    
}
