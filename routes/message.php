<?php
/*
|--------------------------------------------------------------------------
| 知识管理模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:message']],function(){


    //分类
    Route::group(['middleware'=>['permission:message.journ_cate']],function (){
        Route::get('journ_cate','JournCateController@index')->name('admin.journ_cate');
        Route::get('journ_cate/data', 'JournCateController@data')->name('admin.journ_cate.data');
        //添加
        Route::get('journ_cate/create', 'JournCateController@create')->name('admin.journ_cate.create')->middleware('permission:message.journ_cate.create');
        Route::post('journ_cate/store', 'JournCateController@store')->name('admin.journ_cate.store')->middleware('permission:message.journ_cate.create');
        //编辑
        Route::get('journ_cate/{id}/edit', 'JournCateController@edit')->name('admin.journ_cate.edit')->middleware('permission:message.journ_cate.edit');
        Route::put('journ_cate/{id}/update', 'JournCateController@update')->name('admin.journ_cate.update')->middleware('permission:message.journ_cate.edit');
        //删除
        Route::delete('journ_cate/destroy', 'JournCateController@destroy')->name('admin.journ_cate.destroy')->middleware('permission:message.journ_cate.destroy');
        //状态切换
        Route::post('journ_cate/action', 'JournCateController@action')->name('admin.journ_cate.action')->middleware('permission:message.journ_cate.action');

    });


    //资讯列表
    Route::group(['middleware'=>['permission:message.journ']],function (){
        Route::get('journ','JournController@index')->name('admin.journ');
        Route::get('journ/data', 'JournController@data')->name('admin.journ.data');
        //添加
        Route::get('journ/create', 'JournController@create')->name('admin.journ.create')->middleware('permission:message.journ.create');
        Route::post('journ/store', 'JournController@store')->name('admin.journ.store')->middleware('permission:message.journ.create');
        //编辑
        Route::get('journ/{id}/edit', 'JournController@edit')->name('admin.journ.edit')->middleware('permission:message.journ.edit');
        Route::put('journ/{id}/update', 'JournController@update')->name('admin.journ.update')->middleware('permission:message.journ.edit');
        //删除
        Route::delete('journ/destroy', 'JournController@destroy')->name('admin.journ.destroy')->middleware('permission:message.journ.destroy');
        //状态切换
        Route::post('journ/action', 'JournController@action')->name('admin.journ.action')->middleware('permission:message.journ.action');



    });

    //视频列表
    Route::group(['middleware'=>['permission:message.video']],function (){
        Route::get('video','VideoController@index')->name('admin.video');
        Route::get('video/data', 'VideoController@data')->name('admin.video.data');
        //添加
        Route::get('video/create', 'VideoController@create')->name('admin.video.create')->middleware('permission:message.video.create');
        Route::post('video/store', 'VideoController@store')->name('admin.video.store')->middleware('permission:message.video.create');
        //编辑
        Route::get('video/{id}/edit', 'VideoController@edit')->name('admin.video.edit')->middleware('permission:message.video.edit');
        Route::put('video/{id}/update', 'VideoController@update')->name('admin.video.update')->middleware('permission:message.video.edit');
        //删除
        Route::delete('video/destroy', 'VideoController@destroy')->name('admin.video.destroy')->middleware('permission:message.video.destroy');
        //状态切换
        Route::post('video/action', 'VideoController@action')->name('admin.video.action')->middleware('permission:message.video.action');

    });


});