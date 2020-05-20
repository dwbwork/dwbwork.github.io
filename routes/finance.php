<?php
/*
|--------------------------------------------------------------------------
| 财务管理模块
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'permission:finance', 'operate.log']], function () {

    //财务
    Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:affair', 'operate.log']],function() {

        //商家列表
        Route::group(['middleware' => 'permission:affair.finance'], function () {
            Route::get('finance', 'FinanceController@index')->name('admin.finance');
            Route::get('finance/data', 'FinanceController@data')->name('admin.finance.data');
            //删除
            Route::delete('store/destroy', 'FinanceController@destroy')->name('admin.store.destroy')->middleware('permission:store.store.destroy');
           });
    });

    //明细
    Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:fee_detail', 'operate.log']],function() {

        //积分
        Route::group(['middleware' => 'permission:fee_detail.integral_detail'], function () {
            Route::get('integral_detail', 'IntegralDetailController@index')->name('admin.integral_detail');
            Route::get('integral_detail/data', 'IntegralDetailController@data')->name('admin.integral_detail.data');

            //删除
            Route::delete('integral_detail/destroy', 'IntegralDetailController@destroy')->name('admin.integral_detail.destroy')->middleware('permission:integral_detail.integral_detail.destroy');
        });
    });
});
