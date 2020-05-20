<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefreshToken
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
        $token=$request->header('authorization');
        $has=DB::table('oauth_refresh_tokens_ids')->where('access_token',$token)->value('access_token_id');
        if(!$has){
            DB::table('oauth_refresh_tokens_ids')->insert(['access_token'=>$token,'access_token_id'=>Auth::user()->token()->id]);
        }
        return $next($request);
    }
}
