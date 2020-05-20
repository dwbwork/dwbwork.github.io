<?php
/*
|--------------------------------------------------------------------------
| 物联网管理模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:internet']],function(){

    //设备列表
    Route::group(['middleware'=>['permission:internet.plant']],function (){
        Route::get('plant','PlantController@index')->name('admin.plant');
        Route::get('plant/data', 'PlantController@data')->name('admin.plant.data');
        //添加
        Route::get('plant/create', 'PlantController@create')->name('admin.plant.create')->middleware('permission:internet.plant.create');
        Route::post('plant/store', 'PlantController@store')->name('admin.plant.store')->middleware('permission:internet.plant.create');
        //编辑
        Route::get('plant/{id}/edit', 'PlantController@edit')->name('admin.plant.edit')->middleware('permission:internet.plant.edit');
        Route::put('plant/{id}/update', 'PlantController@update')->name('admin.plant.update')->middleware('permission:internet.plant.edit');
        //删除
        Route::delete('plant/destroy', 'PlantController@destroy')->name('admin.plant.destroy')->middleware('permission:internet.plant.destroy');

    });
    //设备分类
    Route::group(['middleware'=>['permission:internet.classify']],function (){
        Route::get('classify','ClassifyController@index')->name('admin.classify');
        Route::get('classify/data', 'ClassifyController@data')->name('admin.classify.data');
        //添加
        Route::get('classify/create', 'ClassifyController@create')->name('admin.classify.create')->middleware('permission:internet.classify.create');
        Route::post('classify/store', 'ClassifyController@store')->name('admin.classify.store')->middleware('permission:internet.classify.create');
        //编辑
        Route::get('classify/{id}/edit', 'ClassifyController@edit')->name('admin.classify.edit')->middleware('permission:internet.classify.edit');
        Route::put('classify/{id}/update', 'ClassifyController@update')->name('admin.classify.update')->middleware('permission:internet.classify.edit');
        //删除
        Route::delete('classify/destroy', 'ClassifyController@destroy')->name('admin.classify.destroy')->middleware('permission:internet.classify.destroy');

    });
    //物联网模块
    Route::group(['middleware'=>['permission:internet.module']],function (){
        Route::get('module','ModuleController@index')->name('admin.module');
        Route::get('module/data', 'ModuleController@data')->name('admin.module.data');
        //添加
        Route::get('module/create', 'ModuleController@create')->name('admin.module.create')->middleware('permission:internet.module.create');
        Route::post('module/store', 'ModuleController@store')->name('admin.module.store')->middleware('permission:internet.module.create');
        //编辑
        Route::get('module/{id}/edit', 'ModuleController@edit')->name('admin.module.edit')->middleware('permission:internet.module.edit');
        Route::put('module/{id}/update', 'ModuleController@update')->name('admin.module.update')->middleware('permission:internet.module.edit');
        //删除
        Route::delete('module/destroy', 'ModuleController@destroy')->name('admin.module.destroy')->middleware('permission:internet.module.destroy');

    });
    //模式管理
    Route::group(['middleware'=>['permission:internet.pattern']],function (){
        Route::get('pattern','PlantController@index')->name('admin.pattern');
        Route::get('pattern/data', 'PlantController@data')->name('admin.pattern.data');
        //添加
        Route::get('pattern/create', 'PlantController@create')->name('admin.pattern.create')->middleware('permission:internet.pattern.create');
        Route::post('pattern/store', 'PlantController@store')->name('admin.pattern.store')->middleware('permission:internet.pattern.create');
        //编辑
        Route::get('pattern/{id}/edit', 'PlantController@edit')->name('admin.pattern.edit')->middleware('permission:internet.pattern.edit');
        Route::put('pattern/{id}/update', 'PlantController@update')->name('admin.pattern.update')->middleware('permission:internet.pattern.edit');
        //删除
        Route::delete('pattern/destroy', 'PlantController@destroy')->name('admin.pattern.destroy')->middleware('permission:internet.pattern.destroy');

    });
    //故障维护
    Route::group(['middleware'=>['permission:internet.fault']],function (){
        Route::get('fault','PlantController@index')->name('admin.fault');
        Route::get('fault/data', 'PlantController@data')->name('admin.fault.data');
        //添加
        Route::get('fault/create', 'PlantController@create')->name('admin.fault.create')->middleware('permission:internet.fault.create');
        Route::post('fault/store', 'PlantController@store')->name('admin.fault.store')->middleware('permission:internet.fault.create');
        //编辑
        Route::get('fault/{id}/edit', 'PlantController@edit')->name('admin.fault.edit')->middleware('permission:internet.fault.edit');
        Route::put('fault/{id}/update', 'PlantController@update')->name('admin.fault.update')->middleware('permission:internet.fault.edit');
        //删除
        Route::delete('fault/destroy', 'PlantController@destroy')->name('admin.fault.destroy')->middleware('permission:internet.fault.destroy');

    });



});
