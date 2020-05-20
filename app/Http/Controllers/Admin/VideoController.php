<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journ as Video;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\JournCate;
class VideoController extends BaseController
{
    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Video();
    }

    public function getModel()
    {
        return new Video();
    }


    /**
     * 条件筛选
     * @param $where
     * @param $model
     * @return mixed
     */
    public function get_where($where,$model){

        if(Auth::user()->company_id>0) {
            $model = $model->where('company_id',Auth::user()->company_id);
        }
        $model = $model->where('type',1);
        return $model;
    }


    /**
     * 创建/更新之前的操作
     * @param $model
     * @param $request
     * @param string $id ID存在表示更新
     */
    protected function beforeSave($model, $id = '')
    {
        if(Auth::user()->company_id) {
            $model->company_id = Auth::user()->company_id;
        }

        return $model;
    }

    /**
     * 附加验证器
     * @param $model 模型
     * @param $id 更新是的对应ID
     * @return bool
     * 返回数组的时候，表示有错误
     */

    protected function extendValidate(Request $request,$model = '', $id = '')
    {
        $validator = Validator::make($request->all(),[ 
            'title' => 'required|string|min:2|max:20',
            'url_list' => 'required|string',
            'content' => 'required|string',
            
        ], [
            'title.required' => '名称不能为空',
            'url_list.required' => '视频不能为空',
            'link.required' => '链接不能为空',
        ],
          [
            'title' => '标题',
            'url_list' => '视频',
            'link' => '链接',
            'content' =>'视频详情'
            
        ]
    );
       if ($validator->fails()) {//异常处理
           $errors = implode(' 和 ',$validator->messages()->all()); 
           return $errors;
           
        }
        return true;
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
    /**
     * 编辑/新增页附带数据
     *
     * @return \Illuminate\Http\Response
     */
    public function CreateEditData($id='')
    {
        $model = JournCate::query();
        if(Auth::user()->company_id >0) {
            $model = $model->where('company_id',Auth::user()->company_id);
        }

        $categories = $model->get();
        return ['categories'=>$categories];
    }

    
}
