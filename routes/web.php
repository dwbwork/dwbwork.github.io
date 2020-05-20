<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('admin.layout'));
});
//文件上传接口，前后台共用
Route::post('uploadImg', 'PublicController@uploadImg')->name('uploadImg');
//发送短信
Route::post('/sendMsg', 'PublicController@sendMsg')->name('sendMsg');

//支付
Route::group(['namespace' => 'Home'], function () {
    //微信支付
    Route::get('/wechatPay', 'PayController@wechatPay')->name('wechatPay');
    //微信支付回调
    Route::post('/wechatNotify', 'PayController@wechatNotify')->name('wechatNofity');
});

//会员-不需要认证
Route::group(['namespace'=>'Home','prefix'=>'member'],function (){
    //注册
    Route::get('register', 'MemberController@showRegisterForm')->name('home.member.showRegisterForm');
    Route::post('register', 'MemberController@register')->name('home.member.register');
    //登录
    Route::get('login', 'MemberController@showLoginForm')->name('home.member.showLoginForm');
    Route::post('login', 'MemberController@login')->name('home.member.login');
});
//会员-需要认证
Route::group(['namespace'=>'Home','prefix'=>'member','middleware'=>'member'],function (){
    //个人中心
    Route::get('/','MemberController@index')->name('home.member');
    //退出
    Route::get('logout', 'MemberController@logout')->name('home.member.logout');
});

//laravel-gii路由
Route::any('/gii/model','\Sunshinev\Gii\Controllers\ModelController@index');
Route::any('/gii/crud','\Sunshinev\Gii\Controllers\CrudController@index');