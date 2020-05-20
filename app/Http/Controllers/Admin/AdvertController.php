<?php
// +----------------------------------------------------------------------
// | 广告图片控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admin;

use App\Models\Advert;

use App\Models\Position;

use EasyWeChat\Kernel\Messages\Article;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::get();

        return view('admin.advert.index',compact('positions'));
    }

    public function data(Request $request)
    {
        $model = Advert::query();
        if ($request->get('position_id')){
            $model = $model->where('position_id',$request->get('position_id'));
        }
        if ($request->get('title')){
            $model = $model->where('title','like','%'.$request->get('title').'%');
        }
        $res = $model->orderBy('sort','desc')->orderBy('id','desc')->with('position')->paginate($request->get('limit',30))->toArray();
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
    public function create(Request $request)
    {
        //所有广告位置
        $positions = Position::orderBy('sort','desc')->get();

        return view('admin.advert.create',compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertRequest $request)
    {

       /* if (Advert::create($request->all())){
            return redirect(route('admin.advert'))->with(['status'=>'添加完成']);
        }
        return redirect(route('admin.advert'))->with(['status'=>'系统错误']);*/
        try{
          
            if($request->bind_id <1){
                return Response::json(['code'=>1,'msg'=>'请填写跳转id',]);
            }
            switch($request->type){//1:机构；2：资讯；3：网上课程
                case 1;$res = Store::where(['id'=>$request->bind_id,'status'=>1])->exists();break;
                case 2;$res = art::where(['id'=>$request->bind_id,'status'=>1])->exists();break;
                case 3;$res = Net::where(['id'=>$request->bind_id,'status'=>1])->exists();break;
            }

            if($res == false){
                return Response::json(['code'=>1,'msg'=>'请检查绑定跳转数据是否存在',]);
            }
            Advert::create($request->all());
            return Response::json(['code'=>0,'msg'=>'添加成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'添加失败','data'=>$exception->getMessage()]);
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
    public function edit(Request $request,$id)
    {
        $advert = Advert::findOrFail($id);
        //所有广告位置
        $positions = Position::orderBy('sort','desc')->get();
        foreach ($positions as $position){
            $position->selected = $position->id == $advert->position_id ? 'selected' : '';
        }
        return view('admin.advert.edit',compact('positions','advert'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdvertRequest $request, $id)
    {

        
        try{
            $this->validate($request,[
                'title'  => 'required|string',
                'sort'  => 'required|numeric',
                'thumb' => 'required|string',
                'position_id' => 'required|numeric',
                
            ]);
            
            $advert = Advert::findOrFail($id);
           
            $advert->update($request->all());
            return Response::json(['code'=>0,'msg'=>'更新成功']);
        }catch (\Exception $exception){
            return Response::json(['code'=>1,'msg'=>'更新失败','data'=>$exception->getMessage()]);
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
        if (Advert::destroy($ids)){
            return response()->json(['code'=>0,'msg'=>'删除成功']);
        }
        return response()->json(['code'=>1,'msg'=>'删除失败']);
    }
}
