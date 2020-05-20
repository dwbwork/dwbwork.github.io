<?php
// +----------------------------------------------------------------------
// | 规格名控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item\ItemSpec;
use App\Models\Item\ItemSkuSpec;
use App\Models\Item\ItemSpecValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\BaseController;
class ItemSpecController extends BaseController
{
    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new ItemSpec();
    }

    public function getModel()
    {
        return new ItemSpec();
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
            
           if(ItemSpec::where('spec_name',$request->spec_name)->exists()){
                return Response::json(['code'=>1,'msg'=>'规格名已存在']);
            }
            if(ItemSpecValue::where(['spec_value'=>$request->spec_value])->exists()){
                return Response::json(['code'=>1,'msg'=>'规格值已存在']);
            }
            $spec = ItemSpec::create($data);
            $data['spec_id'] = $spec->id;
            
            $specValue = ItemSpecValue::create($data);
           DB::commit();
           return Response::json(['code'=>200,'msg'=>'添加成功','data'=>['spec_id'=>$spec->id,'spec_value_id'=>$specValue->id]]);
        }catch (\Exception $exception){
              DB::rollBack();
            return Response::json(['code'=>1,'msg'=>'添加失败','data'=>$exception->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (!is_array($ids) || empty($ids)){
            return Response::json(['code'=>1,'msg'=>'请选择删除项']);
        }
        $category = ItemSkuSpec::where('spec_id',$ids[0])->exists();
        DB::beginTransaction();
        try{
            if ($category){
              return Response::json(['code'=>1,'msg'=>'该规格下有商品，不能删除']);
            }
            ItemSpec::whereIn('spec_id',$ids)->delete();
            DB::commit();
            return Response::json(['code'=>200,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            DB::rollback();
            return Response::json(['code'=>1,'msg'=>'删除失败','data'=>$exception->getMessage()]);
        }
    }

}
