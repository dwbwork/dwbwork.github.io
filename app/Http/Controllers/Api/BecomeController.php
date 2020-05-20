<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 知识管理接口
 * Class BecomeController
 * @package App\Http\Controllers\Api
 */
class BecomeController extends Controller
{
    //资讯详情接口
    public function message(Request $request)
    {
        $res_list = DB::table('message_journ')->where('id',$request->id)->select('title','content','created_at')->get();
        if ($res_list)
        {
            return  response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res_list]);
        }else{
            return  response()->json(['code'=>404,'msg'=>'数据为空','data'=>$res_list]);
        }
    }
    //视频详情接口
    public function video(Request $request)
    {
        $res_list = DB::table('message_journ')->where('id',$request->id)->select('title','content','created_at')->get();
        if ($res_list)
        {
            return  response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res_list]);
        }else{
            return  response()->json(['code'=>404,'msg'=>'数据为空','data'=>$res_list]);
        }
    }
    //知识数据接口
    public function list_news(Request $request)
    {
        if ($request->type = 0)
        {
            $res_list = DB::table('message_journ')
                ->where('recom_type',1)
                ->select('title','url_list','created_at')
                ->orderBy('created_at','desc')
                ->paginate($request->input('limit',6))
                ->toArray();
            if ($res_list)
            {
                return  response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res_list]);
            }else{
                return  response()->json(['code'=>404,'msg'=>'数据为空','data'=>$res_list]);
            }

        }else{
            $res_list = DB::table('message_journ')
                ->where('company_id',$request->id)
                ->select('title','content','created_at')
                ->orderBy('created_at','desc')
                ->paginate($request->input('limit',6))
                ->toArray();            if ($res_list)
            {
                return  response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res_list]);
            }else{
                return  response()->json(['code'=>404,'msg'=>'数据为空','data'=>$res_list]);
            }
        }
    }



}
