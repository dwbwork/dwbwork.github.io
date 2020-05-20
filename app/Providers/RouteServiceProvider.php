<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //接口路由组
        $this->mapApiRoutes();
        //接口商城模块
        $this->mapShopRoutes();
        //默认公共路由组
        $this->mapWebRoutes();
        //后台路由组
        $this->mapAdminRoutes();
        //商城模块路由组
        $this->mapMailRoutes();
        //系统模块路由组
        $this->mapSystemRoutes();
        //系统图片模块路由组
        $this->mapConfigRoutes();
        //会员管理
        $this->mapMemberRoutes();
        //资讯管理
        $this->mapMessageRoutes();
        //问答管理
        $this->mapAnswersRoutes();
        //健康档案管理
        $this->mapRecordsRoutes();
        //商城管理
        $this->mapGoodsRoutes();
        //数据统计
        $this->mapStatisticsRoutes();
        //合作商管理
        $this->mapPartnerRoutes();
        //财务管理
        $this->mapFinanceRoutes();
        //物联网管理
        $this->mapInternetRoutes();
        //营销管理
        $this->mapMarketRoutes();
        //门店管理
        $this->mapStoreRoutes();
        //门店后台
        $this->mapSellerRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /*api接口商城模块*/
    protected function mapShopRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/shop.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }
    //图片管理
    protected function mapPhotoRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/photo.php'));
    }
    //会员管理
    protected function mapMemberRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/member.php'));
    }
    //资讯管理
    protected function mapMessageRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/message.php'));
    }
    //问答管理
    protected function mapAnswersRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/answers.php'));
    }
    //健康档案管理
    protected function mapRecordsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/records.php'));
    }
    //商城管理
    protected function mapGoodsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/goods.php'));
    }
    //数据统计
    protected function mapStatisticsRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/statistics.php'));
    }
    //合作商管理
    protected function mapPartnerRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/partner.php'));
    }
    //财务管理
    protected function mapFinanceRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/finance.php'));
    }
    //物联网管理
    protected function mapInternetRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/internet.php'));
    }
    /*商城模块*/
    protected function mapMailRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/mail.php'));
    }
    /*系统模块*/
    protected function mapSystemRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/system.php'));
    }
    /*系统图片模块*/
    protected function mapConfigRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/config.php'));
    }
    /*营销模块*/
    protected function mapMarketRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/market.php'));
    }
    /*门店模块*/
    protected function mapStoreRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/store.php'));
    }

    /*门店后台*/
    protected function mapSellerRoutes()
    {
        Route::prefix('seller')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/seller/seller.php'));

    }

}
