<?php
/*
|--------------------------------------------------------------------------
| 商城管理模块
|--------------------------------------------------------------------------
*/


Route::group(['middleware' => ['auth', 'permission:mail', 'operate.log']], function () {
     
     //增删改查存放的控制器数组
    $controllers = [
        //商品模块
        'ItemController',
        //商品分类
        'ItemCategoryController',
        //规格名
        'ItemSpecController',
        //规格值
        'ItemSpecValueController'
    ];

     //只有列表和操作的控制器数组
    $controllers_action = [
         //订单模块
        'OrderController'
     ];

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin','middleware' => ['auth', 'permission:item', 'operate.log']], function ($route) use ($controllers){
    

        foreach($controllers as $k=>$c){ 
            //获取控制器名
            $controller = str_replace('Controller', '', $c);
            
            //驼峰转下划线
            $controller_path = strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . '_' . "$2", $controller));
            
            
            Route::group(['middleware' => 'permission:'.$controller_path.'.'.$controller_path], function ($route) use ($c, $controller_path) {
                //查
                Route::get($controller_path.'/data', $c.'@data')->name('admin.'.$controller_path.'.data');
                Route::get($controller_path, ''.$c.'@index')->name('admin.'.$controller_path);
                //增
                Route::get($controller_path.'/create', ''.$c.'@create')->name('admin.'.$controller_path.'.create')->middleware('permission:'.$controller_path.'.'.$controller_path.'.create');
                Route::post($controller_path.'/store', ''.$c.'@store')->name('admin.'.$controller_path.'.store')->middleware('permission:'.$controller_path.'.'.$controller_path.'.create');
                //改
                Route::any($controller_path.'/{id}/edit', ''.$c.'@edit')->name('admin.'.$controller_path.'.edit')->middleware('permission:'.$controller_path.'.'.$controller_path.'.edit');
                Route::put($controller_path.'/{id}/update', ''.$c.'@update')->name('admin.'.$controller_path.'.update')->middleware('permission:'.$controller_path.'.'.$controller_path.'.edit');
                //删
                Route::delete($controller_path.'/destroy', ''.$c.'@destroy')->name('admin.'.$controller_path.'.destroy')->middleware('permission:'.$controller_path.'.'.$controller_path.'.destroy');
                
                 //单个字段操作
                Route::post($controller_path.'/action', ''.$c.'@action')->name('admin.'.$controller_path.'.action')
                ->middleware('permission:'.$controller_path.'.'.$controller_path.'.action');
               });
        };
    });

    
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin','middleware' => 'permission:order','operate.log'], function ($route) use ($controllers_action) {
         foreach($controllers_action as $k=>$c){ 
            //获取控制器名
            $controller = str_replace('Controller', '', $c);
            
            //驼峰转下划线
            $controller_path = strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . '_' . "$2", $controller));
            
                //查
                Route::get($controller_path.'/data', $c.'@data')->name('admin.'.$controller_path.'.data');
                Route::get($controller_path, ''.$c.'@index')->name('admin.'.$controller_path);
                
                 //单个字段操作
                Route::post($controller_path.'/action', ''.$c.'@action')->name('admin.'.$controller_path.'.action')->middleware('permission:'.$controller_path.'.'.$controller_path.'.action');
               }

       });
})
?>
