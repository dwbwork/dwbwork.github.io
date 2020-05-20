<?php
/*
|--------------------------------------------------------------------------
| 会员管理模块
|--------------------------------------------------------------------------
*/

 Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:member', 'operate.log']], function (){
    Route::group(['middleware' => 'permission:member.member'],function(){
        //会员列表页
        Route::get('Member','MemberController@index')->name('admin.member');
        //会员列表数据
        Route::get('Member/data','MemberController@data')->name('admin.member.data');
        
        
    });
 });
