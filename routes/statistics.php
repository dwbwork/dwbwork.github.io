<?php
/*
|--------------------------------------------------------------------------
| 数据统计模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:photo']],function(){

    //统计列表
    Route::group(['middleware'=>['permission:photo.page']],function (){
        Route::get('page','PageController@index')->name('page.photo');

    });


});
