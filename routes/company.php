<?php
/*
|--------------------------------------------------------------------------
| 合作商管理模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:company']],function(){

    //资讯列表
    Route::group(['middleware'=>['permission:company.company']],function (){
        Route::get('company','CompanyController@index')->name('admin.company');
        Route::get('company/data', 'CompanyController@data')->name('admin.company.data');
        //添加
        Route::get('company/create', 'CompanyController@create')->name('admin.company.create')->middleware('permission:company.company.create');
        Route::post('company/store', 'CompanyController@store')->name('admin.company.store')->middleware('permission:company.company.create');
        //编辑
        Route::get('company/{id}/edit', 'CompanyController@edit')->name('admin.company.edit')->middleware('permission:company.company.edit');
        Route::put('company/{id}/update', 'CompanyController@update')->name('admin.company.update')->middleware('permission:company.company.edit');
        //删除
        Route::delete('company/destroy', 'CompanyController@destroy')->name('admin.company.destroy')->middleware('permission:company.company.destroy');
        //状态切换
        Route::post('company/action', 'CompanyController@action')->name('admin.company.action')->middleware('permission:company.company.action');



    });
    


});