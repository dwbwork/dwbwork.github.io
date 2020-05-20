<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\BladeTrait;
use App\Traits\ModelCurlTrait;
use App\Traits\RouteTrait;
use App\Traits\TreeTrait;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use RouteTrait, ModelCurlTrait, BladeTrait,TreeTrait;
    public $routeInfo;//路由信息
    public $module = 'Admin';//模块名字
    public $route;
    public $guardName='admin';//认证类型admin


    public function __construct()
    {
        $this->routeInfo = $this->routeInfo($this->module);

        //共享路由信息到变量
        $this->shareView($this->routeInfo);

        $this->getBlade();
        $this->setModel();


    }

    /**
     * 模板输出
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function display($data = [])
    {

        //取得表名
        $this->getTable();
        $this->pageName();
        $this->commonBlade();
      
        
        return view($this->bladePrefix.$this->bladeView, $data);
    }
}
