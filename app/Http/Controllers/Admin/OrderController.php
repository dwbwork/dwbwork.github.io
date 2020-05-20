<?php
// +----------------------------------------------------------------------
// | 订单管理控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\Item\Order;

class OrderController extends BaseController
{

    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Order();
    }

     /**
     * 获取模型实例.
     *
     * @return void
     */
    public function getModel()
    {
        return new Order();
    }
     
    
    /**
     * 首页附带数据
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
        
        return [];
    }
   
}
