<?php
// +----------------------------------------------------------------------
// | 会员管理控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admin;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;

class MemberController extends BaseController
{

    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Member();
    }

    public function getModel()
    {
        return new Member();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        return view('admin.member.index');
    }

    /**
     * 获取搜索条件
     *
     * @return \Illuminate\Http\Response
     */
    public function get_where($where,$model){
       
        $array=[];
        $where = array_filter($where);
        
       if (@$where['name']){
            $model = $model->where('nick_name','like','%'.$where['name'].'%');
        }
        if (@$where['phone']){
            $model = $model->where('phone','like','%'.$where['phone'].'%');
        }   
        
        return $model;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Moldes\WxUsers  $wxUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {   
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Member::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
}
