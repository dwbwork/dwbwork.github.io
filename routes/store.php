<?php
//商家管理
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'permission:store']], function () {

    //商家列表
    Route::group(['middleware' => 'permission:store.store'], function () {
        Route::get('store/data', 'StoreController@data')->name('admin.store.data');

        Route::get('store', 'StoreController@index')->name('admin.store');
        //添加
        Route::get('store/create', 'StoreController@create')->name('admin.store.create')->middleware('permission:store.store.create');
        Route::post('store/store', 'StoreController@store')->name('admin.store.store')->middleware('permission:store.store.create');
        //编辑
        Route::get('store/{id}/edit', 'StoreController@edit')->name('admin.store.edit')->middleware('permission:store.store.edit');
        Route::put('store/{id}/update', 'StoreController@update')->name('admin.store.update')->middleware('permission:store.store.edit');
        //删除
        Route::delete('store/destroy', 'StoreController@destroy')->name('admin.store.destroy')->middleware('permission:store.store.destroy');
        //操作商家
        Route::any('store/action', 'StoreController@action')->name('admin.store.action')->middleware('permission:store.store.action');
    });

});
?>