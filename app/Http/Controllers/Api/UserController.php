<?php

namespace App\Http\Controllers\Api;
/*
 * 用户控制器
 *
 * */
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WxUsers;
use App\Models\Tag;
use App\Models\Configuration;
use App\Models\SmsLog;
use App\Models\Question;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
class UserController extends Controller
{
    //用户信息
    public function index(Request $request){

        $info = WxUsers::findOrFail($request->user_id);
        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>['info'=>$info]]);
    }

    //修改用户信息
    public function change_user_info(Request $request){

        $data = $request->All();

        $user = WxUsers::findOrFail($request->user_id);
        try {
            //验证验证码
            if($request->phone){
                if(!SmsLog::where(['mobile'=>$request->phone,'code'=>$request->code])->exists()){

                    return Response::json(['code'=>202,'msg'=>'验证码错误']);
                };

                // Cookie::make("phone",$request->phone, time()+3600);
                $user = WxUsers::where('phone',$request->phone)->first();
                $key = Crypt::encryptString($user->phone);
                $token = $user->createToken('TutsForWeb')->accessToken;
                $user->update(['api_token'=>$token,'key'=>$key ]);
                Redis::setex( $request->phone , 36000000 , $token );
                return Response::json(['code'=>200,'msg'=>'换绑成功','data'=>['key'=>$key]]);
                $user->update($data);
            }else{
                $user->update($data);
                return Response::json(['code'=>200,'msg'=>'完善成功']);
            }



        }catch (\Exception $exception){
            return Response::json(['code'=>202,'msg'=>'完善失败','data'=>$exception->getMessage()]);
        }


    }
     
    //发送短信验证码
    public function send_code(Request $request){
        try{
            SmsLog::sendVerifyCode($request->input('phone'));
            return Response::json(['code'=>200,'msg'=>'发送成功']);
        }catch(\Exception $exception){
            return Response::json(['code'=>202,'msg'=>'完善失败','data'=>$exception->getMessage()]);

        }

    }

    /**
     * Login Or Register
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function register(Request $request)
    {
       
        try {

            $this->validate($request, [
                'phone' => 'required|unique:investor',
                'phone' => 'regex:/^1[345789][0-9]{9}$/',     //正则验证
                
            ]);
            
            //验证验证码
            if(!SmsLog::where(['mobile'=>$request->phone,'code'=>$request->code])->exists()){
                    
                return Response::json(['code'=>202,'msg'=>'验证码错误']);
            };

            //判断是登入还是注册
            if(WxUsers::where('phone',$request->phone)->exists()){
                $user = WxUsers::where('phone',$request->phone)->first();
                $key = Crypt::encryptString($user->phone);
                $token = $user->createToken('TutsForWeb')->accessToken;
                $user->update(['api_token'=>$token,'key'=>$key ]);
                
            }else{
                $key =  Crypt::encryptString($request->phone);
                $user = WxUsers::create(['avatar_url'=>'http://bgt-param.oss-cn-hangzhou.aliyuncs.com/2020-03-10_1583805547_5e66f46b12843.png','phone' => $request->input('phone'),
                'nick_name' => '海宁宝' . uniqid(),'key'=>$key,'name' => '海宁宝' . uniqid()]);
                $token = $user->createToken('TutsForWeb')->accessToken; 
                $user->update(['api_token'=>$token]);

            }

            //redis登陆态持久化

            // Cookie::make("phone",$request->phone, time()+3600);

            Redis::setex( $request->phone , 36000000 , $token );
            
               
            return Response::json(['code'=>200,'msg'=>'注册成功,直接登录','data'=>['key'=>$key]]);
        } catch (\Exception $exception) {
            return Response::json(['code' => 202, 'msg' => '注册失败', 'data' => $exception->getMessage()]);

        }
    }


    /*问题解答*/
    public function question(Request $request){
        $res = Question::orderBy('created_at','desc')->paginate($request->input('limit',10))->toArray();
        $res['mobile'] = Configuration::findOrFail(24)->val;
        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }

    /*用户登录协议
    type：1登录协议；2：隐私政策
    */
    public function login_text(Request $request){
        if($request->type == 1){
            $res = Configuration::whereIn('id',[25,26])->get();
        }else{
            $res = Configuration::where('id',27)->get();
        }

        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }




}
