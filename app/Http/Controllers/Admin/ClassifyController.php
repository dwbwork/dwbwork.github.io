<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassifyCreateRequest;
use App\Models\Classify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * 物联网设备分类
 * Class ClassifyController
 * @package App\Http\Controllers\Admin
 */
class ClassifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.classify.index');
    }

    /**
     * 数据列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = Classify::orderBy('sort','desc')->orderBy('id','desc')->paginate($request->get('limit',30))->toArray();
        $data = [
            'code' => 0,
            'msg'   => '正在请求中...',
            'count' => $res['total'],
            'data'  => $res['data']
        ];
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.classify.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassifyCreateRequest $request)
    {
        try{
            Classify::create($request->all());
            //弹窗的传值写法
            return Response::json(['code'=>200,'msg'=>'添加成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>$exception->getMessage()]);
        }

    }
    /**
     * Show the form for editing the specified resource.
     * 编辑设备分类
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $class = Classify::findOrFail($id);
        return view('admin.classify.edit',compact('class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $class = Classify::findOrFail($id);
        try{
            $class->update($request->all());

            return Response::json(['code'=>200,'msg'=>'更新成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>$exception->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     * 删除设备型号
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Classify::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);

    }
}
