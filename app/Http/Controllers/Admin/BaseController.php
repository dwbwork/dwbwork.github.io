<?php
// +----------------------------------------------------------------------
// | Admin模块基类控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
     
    /**
     * 首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
       
       return $this->display($this->indexData() ?: []);
    }


    /**
     * 列表数据接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $model = $this->model->query();
        
        
        if(method_exists($this,'get_where')){
            $model = $this->get_where($request->all() ?? [],$model);
        }

        $res = $model->orderBy('created_at','desc')->paginate($request->get('limit',30));
        
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res->total(),
            'data'  => $res->items(),
        ];
        return Response::json($data);
    }
   /**
     * 新增页面
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
       
        return $this->display($this->CreateEditData() ?: []);
    }

    /**
     * 新增操作
     * @param RoleCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        
        return $this->saveData($request, $this->getModel());
    }

    /**
     * 更新
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request,$id)
    {
        
        $info = $this->model->findOrFail($id);

       
        view()->share('info', $info);
        return $this->display($this->CreateEditData($info) ?: []);
    }

    /**
     * 更新操作
     * @param RoleUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,$id)
    {
        $info = $this->model->findOrFail($id);
        return $this->saveData($request, $info,$id);
    }

    /**
     * 删除功能
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
            return Response::json(['code'=>0,'msg'=>'删除成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'删除失败']);
        }
    }

    /**
     * 更新指定字段操作
     * @param RoleUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function action(Request $request)
    {
        $info = $this->model->findOrFail($request->id);
        try{
            
            $info->update($request->all());
            return Response::json(['code'=>200,'msg'=>'更新成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'更新失败']);
        }
    }

    
}
