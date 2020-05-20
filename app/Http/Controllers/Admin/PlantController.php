<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlantCreateRequest;
use App\Models\Classify;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

/**
 * 设备管理
 * Class PlantController
 * @package App\Http\Controllers\Admin
 */
class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.plant.index');
    }

    /**
     * 设备列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $res = Plant::with(['category:id,category_title'])->orderBy('id','desc')->paginate($request->get('limit',30))->toArray();
       // dd($res);
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
        $cates = DB::table('internet_classify')->select('id','category_title')->get();
        $module = DB::table('internet_module')->select('module_title','id')->get();
        return view('admin.plant.create',compact('cates','module'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlantCreateRequest $request)
    {
        try{
            Plant::create($request->all());

            return Response::json(['code'=>200,'msg'=>'添加成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>$exception->getMessage()]);

        }
    }
    /**
     * Show the form for editing the specified resource.
     * 编辑设备
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cates = DB::table('internet_classify')->select('id','category_title')->get();
        $module = DB::table('internet_module')->select('module_title','id')->get();
        $plants = Plant::findOrFail($id);
        return view('admin.plant.edit',compact('cates','module','plants'));

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
        $plants = Plant::findOrFail($id);
        try{
            $plants->update($request->all());

            return Response::json(['code'=>200,'msg'=>'更新成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>$exception->getMessage()]);
        }
    }

    /**
     * 删除设备
     * Remove the specified resource from storage.
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Plant::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
}
