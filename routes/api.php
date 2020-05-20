<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//文件上传接口
Route::post('upload', 'ApiController@upload')->name('api.upload');

//视频上传
Route::post('VideoUpload', 'ApiController@VideoUpload')->name('api.VideoUpload');

Route::post('oneUpload', 'ApiController@one_upload')->name('api.OneUpload');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Api接口路由组
Route::group(['namespace'=>'Api','perfix'=>'api'],function(){

    /*微信登陆授权*/
    Route::post('mine/getUserInfo','MineController@getUserInfo');

    /*获取微信用户信息*/
    Route::post('mine','MineController@index');

    /*首页*/
    Route::any('index','IndexController@index');
    /*图标管理*/
    Route::any('icon','IndexController@icon');
    /*资讯和视频接口*/
    Route::any('get_article','IndexController@get_article');
    /*返回资讯接口*/
    Route::any('message','BecomeController@message');





});

    /*用户登录协议*/
    Route::any('user/login_text','Api\UserController@login_text');

    //微信支付回调
    Route::post('wechat_pay/notify','Home\WechatPayController@notify');

    /*发送短信验证码 */
    Route::any('user/send_code','Api\UserController@send_code');
 
    /*注册 */
    Route::post('user/register','Api\UserController@register');

    /*登录 */
    Route::any('user/login','Api\UserController@login');

    /*问题解答*/
    Route::any('user/question','Api\UserController@question');
