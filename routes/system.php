<?php

/*
|--------------------------------------------------------------------------
| 系统管理模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>['auth','permission:system']],function (){

    //用户管理
    Route::group(['middleware'=>['permission:system.user']],function (){
        Route::get('user','UserController@index')->name('admin.user');
        Route::get('user/data','UserController@data')->name('admin.user.data');
        //添加
        Route::get('user/create','UserController@create')->name('admin.user.create')->middleware('permission:system.user.create');
        Route::post('user/store','UserController@store')->name('admin.user.store')->middleware('permission:system.user.create');
        //编辑
        Route::get('user/{id}/edit','UserController@edit')->name('admin.user.edit')->middleware('permission:system.user.edit');
        Route::post('user/{id}/update','UserController@update')->name('admin.user.update')->middleware('permission:system.user.edit');
        //删除
        Route::delete('user/destroy','UserController@destroy')->name('admin.user.destroy')->middleware('permission:system.user.destroy');
        //分配角色
        Route::get('user/{id}/role','UserController@role')->name('admin.user.role')->middleware('permission:system.user.role');
        Route::put('user/{id}/assignRole','UserController@assignRole')->name('admin.user.assignRole')->middleware('permission:system.user.role');
        //分配权限
        Route::get('user/{id}/permission','UserController@permission')->name('admin.user.permission')->middleware('permission:system.user.permission');
        Route::put('user/{id}/assignPermission','UserController@assignPermission')->name('admin.user.assignPermission')->middleware('permission:system.user.permission');
    });

    //角色管理
    Route::group(['middleware'=>'permission:system.role'],function (){
        Route::get('role','RoleController@index')->name('admin.role');
        Route::get('role/data','RoleController@data')->name('admin.role.data');
        //添加
        Route::get('role/create','RoleController@create')->name('admin.role.create')->middleware('permission:system.role.create');
        Route::post('role/store','RoleController@store')->name('admin.role.store')->middleware('permission:system.role.create');
        //编辑
        Route::get('role/{id}/edit','RoleController@edit')->name('admin.role.edit')->middleware('permission:system.role.edit');
        Route::put('role/{id}/update','RoleController@update')->name('admin.role.update')->middleware('permission:system.role.edit');
        //删除
        Route::delete('role/destroy','RoleController@destroy')->name('admin.role.destroy')->middleware('permission:system.role.destroy');
        //分配权限
        Route::get('role/{id}/permission','RoleController@permission')->name('admin.role.permission')->middleware('permission:system.role.permission');
        Route::put('role/{id}/assignPermission','RoleController@assignPermission')->name('admin.role.assignPermission')->middleware('permission:system.role.permission');
    });

    //权限管理
    Route::group(['middleware'=>'permission:system.permission'],function (){
        Route::get('permission','PermissionController@index')->name('admin.permission');
        Route::get('permission/data','PermissionController@data')->name('admin.permission.data');
        //添加
        Route::get('permission/create','PermissionController@create')->name('admin.permission.create')->middleware('permission:system.permission.create');
        Route::post('permission/store','PermissionController@store')->name('admin.permission.store')->middleware('permission:system.permission.create');
        //编辑
        Route::get('permission/{id}/edit','PermissionController@edit')->name('admin.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('permission/{id}/update','PermissionController@update')->name('admin.permission.update')->middleware('permission:system.permission.edit');
        //删除
        Route::delete('permission/destroy','PermissionController@destroy')->name('admin.permission.destroy')->middleware('permission:system.permission.destroy');
    });

    //配置组
    Route::group(['middleware'=>'permission:system.config_group'],function (){
        Route::get('config_group','ConfigGroupController@index')->name('admin.config_group');
        Route::get('config_group/data','ConfigGroupController@data')->name('admin.config_group.data');
        //添加
        Route::get('config_group/create','ConfigGroupController@create')->name('admin.config_group.create')->middleware('permission:system.config_group.create');
        Route::post('config_group/store','ConfigGroupController@store')->name('admin.config_group.store')->middleware('permission:system.config_group.create');
        //编辑
        Route::get('config_group/{id}/edit','ConfigGroupController@edit')->name('admin.config_group.edit')->middleware('permission:system.config_group.edit');
        Route::put('config_group/{id}/update','ConfigGroupController@update')->name('admin.config_group.update')->middleware('permission:system.config_group.edit');
        //删除
        Route::delete('config_group/destroy','ConfigGroupController@destroy')->name('admin.config_group.destroy')->middleware('permission:system.config_group.destroy');
    });

    //配置项
    Route::group(['middleware'=>'permission:system.configuration'],function (){
        Route::get('configuration','ConfigurationController@index')->name('admin.configuration');
        //添加
        Route::get('configuration/create','ConfigurationController@create')->name('admin.configuration.create')->middleware('permission:system.configuration.create');
        Route::post('configuration/store','ConfigurationController@store')->name('admin.configuration.store')->middleware('permission:system.configuration.create');
        //编辑
        Route::put('configuration/update','ConfigurationController@update')->name('admin.configuration.update')->middleware('permission:system.configuration.edit');
        //删除
        Route::delete('configuration/destroy','ConfigurationController@destroy')->name('admin.configuration.destroy')->middleware('permission:system.configuration.destroy');
    });
    //登录日志
    Route::group(['middleware'=>'permission:system.login_log'],function (){
        Route::get('login_log','LoginLogController@index')->name('admin.login_log');
        Route::get('login_log/data','LoginLogController@data')->name('admin.login_log.data');
        Route::delete('login_log/destroy','LoginLogController@destroy')->name('admin.login_log.destroy');
    });

    //操作日志
    Route::group(['middleware'=>'permission:system.operate_log'],function (){
        Route::get('operate_log','OperateLogController@index')->name('admin.operate_log');
        Route::get('operate_log/data','OperateLogController@data')->name('admin.operate_log.data');
        Route::delete('operate_log/destroy','OperateLogController@destroy')->name('admin.operate_log.destroy');
    });

    //金刚区管理
    Route::group(['middleware'=>'permission:system.icon'],function (){
        Route::get('icon','IconController@index')->name('admin.icon');
        Route::get('icon/data','IconController@data')->name('admin.icon.data');
        //添加
        Route::get('icon/create','IconController@create')->name('admin.icon.create')->middleware('permission:system.icon.create');
        Route::post('icon/store','IconController@store')->name('admin.icon.store')->middleware('permission:system.icon.create');
        //编辑
        Route::get('icon/{id}/edit','IconController@edit')->name('admin.icon.edit')->middleware('permission:system.icon.edit');
        Route::put('icon/{id}/update','IconController@update')->name('admin.icon.update')->middleware('permission:system.icon.edit');
        //删除
        Route::delete('icon/destroy','IconController@destroy')->name('admin.icon.destroy')->middleware('permission:system.icon.destroy');
    });

     //Shell化生成模块
  
   Route::get('artisan/create','ArtisanController@create')->name('admin.artisan.create')->middleware('permission:system.artisan.create');
    Route::any('artisan/store','ArtisanController@store')->name('admin.artisan.store')->middleware('permission:system.artisan.create');

});
