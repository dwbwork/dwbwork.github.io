<?php
/*
|--------------------------------------------------------------------------
| 合作商管理模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:partner']],function(){

    //渠道商管理

    Route::group(['middleware'=>['permission:partner.company']],function (){
        Route::get('company','CompanyController@index')->name('admin.company');
        Route::get('company/data', 'CompanyController@data')->name('admin.company.data');
        //添加
        Route::get('company/create', 'CompanyController@create')->name('admin.company.create')->middleware('permission:partner.company.create');
        Route::post('company/store', 'CompanyController@store')->name('admin.company.store')->middleware('permission:partner.company.create');
        //编辑
        Route::get('company/{id}/edit', 'CompanyController@edit')->name('admin.company.edit')->middleware('permission:partner.company.edit');
        Route::put('company/{id}/update', 'CompanyController@update')->name('admin.company.update')->middleware('permission:partner.company.edit');
        //删除
        Route::delete('company/destroy', 'CompanyController@destroy')->name('admin.company.destroy')->middleware('permission:partner.company.destroy');
        //状态切换
        Route::post('company/action', 'CompanyController@action')->name('admin.company.action')->middleware('permission:partner.company.action');



    });


});
