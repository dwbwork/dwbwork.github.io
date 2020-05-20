<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/11 0011
 * Time: 16:59
 */
Route::group(['namespace'=>'Seller','prefix'=>'seller','middleware'=>'auth.admin:seller'],function () {

    //后台布局
    Route::get('/','IndexController@layout')->name('seller.layout');
    //后台首页
    Route::get('/index','IndexController@index')->name('seller.index');

});
Route::group(['namespace'=>'Seller','prefix'=>'seller'],function (){

    Route::get('/seller', 'IndexController@index');
    //登录
    Route::get('/login','UserController@showLoginForm')->name('seller.user.loginForm');
    Route::post('/login','UserController@login')->name('seller.user.login');
    //退出
    Route::get('/logout','UserController@logout')->name('seller.user.logout')->middleware('auth.admin:seller');
    //更改密码
    Route::get('change_my_password_form','UserController@changeMyPasswordForm')->name('seller.user.changeMyPasswordForm')->middleware('auth.admin:seller');
    Route::post('change_my_password','UserController@changeMyPassword')->name('seller.user.changeMyPassword')->middleware('auth.admin:seller');
});

/*
|--------------------------------------------------------------------------
| 系统管理模块
|--------------------------------------------------------------------------
*/
Route::group(['namespace'=>'Seller','prefix'=>'seller','middleware'=>['auth','permission:system_seller']],function (){

    //用户管理
    Route::group(['middleware'=>['permission:system_seller.user']],function (){
        Route::get('user','UserController@index')->name('seller.user');
        Route::get('user/data','UserController@data')->name('seller.user.data');
        //添加
        Route::get('user/create','UserController@create')->name('seller.user.create')->middleware('permission:system_seller.user.create');
        Route::post('user/store','UserController@store')->name('seller.user.store')->middleware('permission:system_seller.user.create');
        //编辑
        Route::get('user/{id}/edit','UserController@edit')->name('seller.user.edit')->middleware('permission:system_seller.user.edit');
        Route::post('user/{id}/update','UserController@update')->name('seller.user.update')->middleware('permission:system_seller.user.edit');
        //删除
        Route::delete('user/destroy','UserController@destroy')->name('seller.user.destroy')->middleware('permission:system_seller.user.destroy');
        //分配角色
        Route::get('user/{id}/role','UserController@role')->name('seller.user.role')->middleware('permission:system_seller.user.role');
        Route::put('user/{id}/assignRole','UserController@assignRole')->name('seller.user.assignRole')->middleware('permission:system_seller.user.role');
        //分配权限
        Route::get('user/{id}/permission','UserController@permission')->name('seller.user.permission')->middleware('permission:system_seller.user.permission');
        Route::put('user/{id}/assignPermission','UserController@assignPermission')->name('seller.user.assignPermission')->middleware('permission:system_seller.user.permission');
    });

    //角色管理
    Route::group(['middleware'=>'permission:system_seller.role'],function (){
        Route::get('role','RoleController@index')->name('seller.role');
        Route::get('role/data','RoleController@data')->name('seller.role.data');
        //添加
        Route::get('role/create','RoleController@create')->name('seller.role.create')->middleware('permission:system_seller.role.create');
        Route::post('role/store','RoleController@store')->name('seller.role.store')->middleware('permission:system_seller.role.create');
        //编辑
        Route::get('role/{id}/edit','RoleController@edit')->name('seller.role.edit')->middleware('permission:system_seller.role.edit');
        Route::put('role/{id}/update','RoleController@update')->name('seller.role.update')->middleware('permission:system_seller.role.edit');
        //删除
        Route::delete('role/destroy','RoleController@destroy')->name('seller.role.destroy')->middleware('permission:system_seller.role.destroy');
        //分配权限
        Route::get('role/{id}/permission','RoleController@permission')->name('seller.role.permission')->middleware('permission:system_seller.role.permission');
        Route::put('role/{id}/assignPermission','RoleController@assignPermission')->name('seller.role.assignPermission')->middleware('permission:system_seller.role.permission');
    });

    //权限管理
    Route::group(['middleware'=>'permission:system_seller.permission'],function (){
        Route::get('permission','PermissionController@index')->name('seller.permission');
        Route::get('permission/data','PermissionController@data')->name('seller.permission.data');
        //添加
        Route::get('permission/create','PermissionController@create')->name('seller.permission.create')->middleware('permission:system_seller.permission.create');
        Route::post('permission/store','PermissionController@store')->name('seller.permission.store')->middleware('permission:system_seller.permission.create');
        //编辑
        Route::get('permission/{id}/edit','PermissionController@edit')->name('seller.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('permission/{id}/update','PermissionController@update')->name('seller.permission.update')->middleware('permission:system.permission.edit');
        //删除
        Route::delete('permission/destroy','PermissionController@destroy')->name('seller.permission.destroy')->middleware('permission:system.permission.destroy');
    });

    //配置组
    Route::group(['middleware'=>'permission:system.config_group'],function (){
        Route::get('config_group','ConfigGroupController@index')->name('seller.config_group');
        Route::get('config_group/data','ConfigGroupController@data')->name('seller.config_group.data');
        //添加
        Route::get('config_group/create','ConfigGroupController@create')->name('seller.config_group.create')->middleware('permission:system.config_group.create');
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




});

