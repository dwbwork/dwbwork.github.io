<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\JournCreateRequest;
use App\Models\Journ;
use App\Models\JournCate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
/**
 * 资讯列表
 * Class JournController
 * @package App\Http\Controllers\Admin
 */
class JournController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.journ.index');
    }

    /**
     * 数据列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $model = Journ::query();
        if(Auth::user()->company_id>0) {
            $model = $model->where('company_id',Auth::user()->company_id);
        }
        $res = $model->where('type',0)
            ->paginate($request->get('limit',30))->toArray();
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
        $model = JournCate::query();
        if(Auth::user()->company_id >0) {
             $model = $model->where('company_id',Auth::user()->company_id);
        }

        $categories = $model->get();
        return view('admin.journ.create')->with(['categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JournCreateRequest $request)
    {
        try{
            $data =$request->except('dosubmit');
            $data['company_id'] = Auth::user()->company_id;
            Journ::create($data);

            return Redirect::to(URL::route('admin.journ'))->with(['success'=>'添加成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $journ = Journ::findOrFail($id);
        $model = JournCate::query();
        if(Auth::user()->company_id >0) {
            $model = $model->where('company_id',Auth::user()->company_id);
        }

        $categories = $model->get();
        return view('admin.journ.edit',compact('journ','categories'));
    }

    /**
     * Update the specified resource in storage.
     * 更新资讯
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $journ = Journ::findOrFail($id);
        try{

            $journ->update($request->all());

            return Redirect::to(URL::route('admin.journ'))->with(['success'=>'成功']);
        }catch (\Exception $exception){
            return Redirect::back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids)){
            return response()->json(['code'=>1,'msg'=>'请选择删除项']);
        }
        if (Journ::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }

    /**
     * 状态切换
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {
        $info = Journ::findOrFail($request->id);
        try{

            $info->update($request->all());
            return Response::json(['code'=>200,'msg'=>'更新成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'更新失败']);
        }
    }
}
