<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //左侧菜单
        view()->composer('admin.layout',function($view){
            //系统模块
            $menus = \App\Models\Permission::with(['childs'=>function($query){
                $query->where(['status'=>1,'type'=>2])->with(['childs'=>function($query){
                    $query->where(['status'=>1,'type'=>2])->get();
                }]);
            }
            ])->where(['parent_id'=>0,'status'=>1,'module'=>1])->orderBy('sort','asc')->get();
            //商城模块
            $menus2 = \App\Models\Permission::with(['childs'=>function($query){
                $query->where(['status'=>1,'type'=>2])->with(['childs'=>function($query){
                    $query->where(['status'=>1,'type'=>2])->get();
                }]);
            }
            ])->where(['parent_id'=>0,'status'=>1,'module'=>2])->orderBy('sort','asc')->get();
            //物联网模块
            $menus3 = \App\Models\Permission::with(['childs'=>function($query){
                $query->where(['status'=>1,'type'=>2])->with(['childs'=>function($query){
                    $query->where(['status'=>1,'type'=>2])->get();
                }]);
            }
            ])->where(['parent_id'=>0,'status'=>1,'module'=>3])->orderBy('sort','asc')->get();
            //相关配置
            $logo = \App\Models\Configuration::findOrFail(28)->val;
            
            $web_name = \App\Models\Configuration::findOrFail(5)->val;

            $view->with(['menus'=>$menus,'menus2'=>$menus2,'menus3'=>$menus3,'logo'=>$logo,'web_name'=>$web_name]);
        });
    }


}
