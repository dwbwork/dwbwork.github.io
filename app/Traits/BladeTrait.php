<?php
// +----------------------------------------------------------------------
// | 模板类
// +----------------------------------------------------------------------
// | 2020.4.3
// +----------------------------------------------------------------------
// | Author: zhou
// +----------------------------------------------------------------------
namespace App\Traits;
trait BladeTrait
{

    public $bladeView;
    public $bladePrefix='';


    /**
     * 输出视图
     * @param array $data
     * @return mixed
     */
    public function display($data = [])
    {


        $this->commonBlade();


        return view($this->bladePrefix.$this->bladeView, $data);
    }



    public function setModelControllerView($view_name = '')
    {
        $route_info = $this->routeInfo;
        $controller=$this->toModelBlade($route_info['controller_base']) . '.' ;
       
        $view_name ? $this->bladeView =$this->toModelBlade($this->module). '.' .$controller . $view_name : '';


    }

    public function setModelView($view_name = '')
    {

        $view_name ? $this->bladeView = $this->toModelBlade($this->module) . '.' . $view_name : '';
    }
    
    /*
    *模板页驼峰命名
    */
    public function toModelBlade($path){
        $arr= explode("\\",$path);
        
        foreach ($arr as $key => $value) {
            $arr[$key] = strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . '_' . "$2", $value));
        }
        return strtolower(implode(".",$arr));
    }

    /**
     * 是否视图目录取消控制器目录
     * @return bool
     */
    public function viewNotControllerBlade(){
        return false;
    }

    public function getBlade()
    {
        $route_info = $this->routeInfo;
        $controller=$this->toModelBlade($route_info['controller_base']) . '.' ;
        if($this->viewNotControllerBlade())
        {
            $controller='';
        }

        $this->bladeView =$this->toModelBlade($this->module) . '.' .$controller . $route_info['action_name'];

    }

    /**
     * 通用设置
     */
    public function commonBlade()
    {

    }
}