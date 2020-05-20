<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



/**
 * 首页接口
 * Class IndexController
 * @package App\Http\Controllers\Api
 */
class IndexController extends Controller
{
    //轮播图管理
    public function index()
    {
        $images = DB::table('adverts')->select('thumb','link')->get();
        if ($images)
        {
            return  response()->json(['code'=>200,'msg'=>'获取成功','data'=>$images]);
        }else{
            return  response()->json(['code'=>404,'msg'=>'数据为空','data'=>$images]);
        }
    }
    //图标接口
    public function icon()
    {
        $icon = DB::table('icon')->select('name','thumb','link','company_id','sort')->get();
        if ($icon)
        {
            return  response()->json(['code'=>200,'msg'=>'获取成功','data'=>$icon]);
        }else{
            return  response()->json(['code'=>404,'msg'=>'数据为空','data'=>$icon]);
        }
    }
    //资讯接口
    public function get_article(Request $request)
    {

        $res_list = DB::table('message_journ')
            ->where('status',1)
            ->select('title','url_list','created_at')
            ->orderBy('created_at','desc')
            ->paginate($request->input('limit',6))
            ->toArray();
        if ($res_list){
            return  response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res_list]);
        }else{
            return  response()->json(['code'=>404,'msg'=>'数据为空','data'=>$res_list]);

        }

    }



}
