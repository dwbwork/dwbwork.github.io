<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item\ItemCategory;
use App\Http\Controllers\Admin\BaseController;

class ItemCategoryController extends BaseController
{
    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new ItemCategory();
    }

    public function getModel()
    {
        return new ItemCategory();
    }
    
    /**
     * 首页附带数据
     *
     * @return \Illuminate\Http\Response
     */
    public function indexData()
    {
    	 $categories = ItemCategory::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->get();
        
        return ['categories'=>$categories];
    }

    /**
     * 编辑/新增页附带数据
     *
     * @return \Illuminate\Http\Response
     */
    public function CreateEditData($id='')
    {
         $categories = ItemCategory::with('allChilds')->where('parent_id',0)->orderBy('sort','asc')->get();
        
        return ['categories'=>$categories];
    }

    
}
