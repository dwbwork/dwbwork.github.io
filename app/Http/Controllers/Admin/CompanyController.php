<?php
// +----------------------------------------------------------------------
// |合作商控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Validator;
class CompanyController extends BaseController
{
    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new Company();
    }

    public function getModel()
    {
        return new Company();
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
            'name' => 'required|string',
            'thumb' => 'required|string',
            'link' => 'required|string|min:10',
        ], [
            'name.required' => '名称不能为空',
            'thumb.required' => '图标不能为空',
            'link.required' => '链接不能为空',
        ],
            [
                'name' => '图标',
                'thumb' => '图标',
                'link' => '链接',

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

        return [];
    }


}
