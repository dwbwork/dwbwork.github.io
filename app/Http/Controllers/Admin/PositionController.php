<?php

namespace App\Http\Controllers\Admin;

use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Requests\PositionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.position.index');
    }

    public function data(Request $request)
    {
        $res = Position::orderBy('sort','desc')->orderBy('id','desc')->paginate($request->get('limit',30))->toArray();
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
        return view('admin.position.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionRequest $request)
    {
       
        try{
            $this->validate($request,[
                'name'  => 'required|string',
                'sort'  => 'required|numeric'
            ]);

            Position::create($request->all());
            return Response::json(['code'=>0,'msg'=>'成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'失败','data'=>$exception->getMessage()]);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $position = Position::findOrFail($id);
        if (!$position){
            return redirect(route('admin.position'))->withErrors(['status'=>'分类不存在']);
        }
        return view('admin.position.edit',compact('position'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PositionRequest $request, $id)
    {


        try{
            $this->validate($request,[
                'name'  => 'required|string',
                'sort'  => 'required|numeric'
            ]);
            $position=Position::findOrFail($id);
            $position->update($request->only(['name','sort']));
            return Response::json(['code'=>0,'msg'=>'成功']);

        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'失败','data'=>$exception->getMessage()]);
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
        $position = Position::with('adverts')->find($ids);
        if (!$position){
            return response()->json(['code'=>1,'msg'=>'数据不存在']);
        }
        if (!$position->adverts->isEmpty()){
            return response()->json(['code'=>1,'msg'=>'该广告位下存在广告信息，不能删除']);
        }
        if ($position->delete()){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
}
