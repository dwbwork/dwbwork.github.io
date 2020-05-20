<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redis;
use App\Models\Member;
use App\Models\Item\Order;
use Illuminate\Support\Facades\Crypt;
class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

       /* $redis = Redis::connection('publisher');
        $redis->setex('ORDER_CONFIRM:11',20,10);

        $redis->subscribe(['__keyevent@*__:expired'], function ($channel) {


                    $order = Order::findOrFail(12);
                    $order->pay_type=2;
                    $order->update();


        });*/
        //实例化redis
        $redis = Redis::connection();

        //验证失败
       if(!$request->key || $request->key=="undefined" ||!$redis->exists(Crypt::decryptString(@$request->key)) ){
           

           //根据键名获取键值
           
           return Response()->json(['code'=>400,'msg'=>'登录异常,请重新登录']);
       };

        $phone = Crypt::decryptString(@$request->key);
        $user = Member::where('phone',$phone)->first();
        
        $request->user_id =$user->id;

        //持久化登录态
        Redis::setex($phone , 36000000 , $user->api_token );
       //验证成功
        return $next($request);
    }
}
