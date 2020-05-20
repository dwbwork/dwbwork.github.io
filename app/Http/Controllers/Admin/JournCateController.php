<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\JournCate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class JournCateController extends BaseController
{
    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new JournCate();
    }

    public function getModel()
    {
        return new JournCate();
    }


    /**
     * 条件筛选
     * @param $where
     * @param $model
     * @return mixed
     */
    public function get_where($where,$model){

        if(Auth::user()->company_id >0) {
            $model = $model->where('company_id',Auth::user()->company_id);
        }

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
            'name' => 'required|string|min:2|max:20',


        ], [
            'name.required' => '分类名不能为空',

        ],
            [
                'name' => '标题',


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
        $categories = Company::get();
        return ['categories'=>$categories];
    }
}
