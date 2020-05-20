<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WxUsers;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use App\Models\Topic;
use App\Models\Message;
use App\Models\history;
class MineController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,WxUsers $WxUsers)
    {
        $openid = $request->input('openid');
        $data = WxUsers::where('openid',$openid)->get();

        if(DB::table('messages')->where(['user_id'=>$data[0]['id'],'read'=>1])->count()>0){
            $have_message =1;
        }else{
            $have_message =0;
        }
        return response()->json(['code'=>200,'msg'=>'获取成功','data'=>$data,'hava_message'=>$have_message]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserInfo(Request $request)
    {
         $code = $request->input('code');

         $appid = env('WX_APPID');
         $secret = env('WX_SECRET');

        //取得openid
        //$oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $oauth2Url = file_get_contents('https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code');

        $oauth2 =json_decode($oauth2Url,true);


        $user['openid'] = $oauth2['openid'];
       // $user['session_key'] = $oauth2['session_key'];
        $user['nick_name'] = 'sy'.rand(10,9999);

        /*添加用户信息*/
        if(DB::table('wx_users')->where('openid',$user['openid'])->first()){
            return response()->json(['code'=>200,'msg'=>'信息获取成功','data'=>['openid'=>$user['openid']]]);

        }else{

            $res = WxUsers::create($user);
            if($res){
                return response()->json(['code'=>200,'msg'=>'信息获取成功','data'=>['openid'=>$user['openid']]]);
            }
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function aboutUs(Request $request)
    {
        $article = Article::where('category_id',3)->first();
        
      return response()->json(['code'=>200,'msg'=>'获取成功','data'=>['article'=>$article]]);  
    }

    /**
     *我的发布
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function my_show(Request $request)
    { 
        //验证用户信息
        
        $userInfo= WxUsers::where('openid',$request->only('openid'))->get();
        
        if(!$userInfo){
            return Response()->json(['code'=>202,'msg'=>'用户信息异常']);
        };
        $total_page = ceil(DB::table('topic')->where('user_id',$userInfo[0]['id'])->count()/($request->input('limit')));
        $topic_list= Topic::where('user_id',$userInfo[0]['id'])->orderBy('created_at','desc')
            ->paginate($request->input('limit',1))->toArray();

        return response()->json(['code'=>200,'msg'=>'获取成功','data'=>['total_page'=>$total_page,'page'=>$request->input('page',0),'topic_list'=>$topic_list]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    /**浏览记录
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
         //验证用户信息
        $userInfo= WxUsers::where('openid',$request->only('openid'))->get();
        
        if(!$userInfo){
            return Response()->json(['code'=>202,'msg'=>'用户信息异常']);
        };


        $total_page = ceil(DB::table('history')->where('user_id',$userInfo[0]['id'])->count()/($request->input('limit')));


        $list = history::query()->where('user_id',$userInfo[0]['id'])->orderBy('created_at','desc')
            ->with(['topic'])
            ->paginate($request->input('limit',10))
            ->toArray();

        foreach($list['data'] as $k=>$v){
            // dump(DB::table('wx_users')->where('id',$v['topic']['user_id'])->first()->avatar_url);die;

            $list['data'][$k]['topic']['avatar']=DB::table('wx_users')->where('id',$v['topic']['user_id'])->first()->avatar_url ??'';
               
            }
        
       
       return response()->json(['code'=>200,'msg'=>'获取成功','data'=>['total_page'=>$total_page,'page'=>$request->input('page',0),'list'=>$list]]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUserInfo(Request $request)
    {
        $info = $request->only(['openid','avatar_url','nick_name']);

        $info['nick_name'] = $this->filterEmoji($info['nick_name']);
        $res = WxUsers::where('openid',$info['openid'])->update($info);

        if($res){
            return response()->json(['code'=>200,'msg'=>'更新成功']);

        }
    }
    public function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function message(Request $request)
    {
        //验证用户信息
        $userInfo= WxUsers::where('openid',$request->only('openid'))->first();

        if(!$userInfo){
            return Response()->json(['code'=>202,'msg'=>'用户信息异常']);
        };

        $total_page = ceil(DB::table('messages')->where('user_id',$userInfo['id'])->count()/($request->input('limit')));

        $list = Message::query()->where('user_id',$userInfo['id'])->orderBy('created_at','desc')
            ->paginate($request->input('limit',1))
            ->toArray();

        return response()->json(['code'=>200,'msg'=>'获取成功','data'=>['total_page'=>$total_page,'page'=>$request->input('page',0),'list'=>$list['data']]]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function set_message(Request $request){
        //验证用户信息
        $userInfo= WxUsers::where('openid',$request->only('openid'))->first();

        if(!$userInfo){
            return Response()->json(['code'=>202,'msg'=>'用户信息异常']);
        };
        $res = Message::where('id',$request->input('id'))->update(['read'=>2]);

        
        return response()->json(['code'=>200,'msg'=>'获取成功']);

    }

}
