<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| 商城模块路由
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*用户地址路由*/
Route::group(['namespace'=>'Api','perfix'=>'api','middleware'=>'member'],function(){

    //地址列表
    Route::any('member_address','MemberAddressController@address_list');
    //新增或修改地址
    Route::any('member_address/add','MemberAddressController@add_address');
    //删除地址
    Route::any('member_address/del','MemberAddressController@del');


});

/*用户购物车路由*/
Route::group(['namespace'=>'Api','perfix'=>'api','middleware'=>'member'],function(){

    //添加购物车
    Route::any('cart/add','CartController@add');
    //购物车列表
    Route::any('cart/cart_list','CartController@cart_list');
    //删除购物车
    Route::any('member_address/del','MemberAddressController@del');


});

/*课程订单路由组*/
Route::group(['namespace'=>'Api','perfix'=>'api','middleware'=>'member'],function(){



    /*订单列表*/
    Route::any('order/order','OrderController@order');

    /*评价订单*/
    Route::any('order/push_comment','OrderController@push_comment');

    /*提交订单*/
    Route::any('order/add_order','OrderController@add_order');

    /*购物车提交订单*/
    Route::any('order/add_cart_order','OrderController@add_cart_order');



    /*订单详情*/
    Route::any('order/order_detail','OrderController@order_detail');

    /*我的评价*/
    Route::any('order/my_comment','OrderController@my_comment');

    /*删除评价*/
    Route::any('order/del_comment','OrderController@del_comment');

    /*订单填写信息*/
    Route::any('order/write_info','OrderController@write_info');

});




