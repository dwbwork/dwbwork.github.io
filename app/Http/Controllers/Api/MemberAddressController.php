<?php
// +----------------------------------------------------------------------
// | 用户收货地址控制器
// +----------------------------------------------------------------------
// | 2020-4-10
// +----------------------------------------------------------------------
// | Author: 周`
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\MemberAddress;
use Illuminate\Support\Facades\Validator;
class MemberAddressController extends Controller
{
    /**
     * 实例化一个模型实例.
     *
     * @return void
     */
    public function address_list(Request $request)
    {

        $res = MemberAddress::where('user_id',$request->user_id)
            ->orderBy('created_at','desc')
            ->paginate($request->input('limit',10))
            ->toArray();

        return Response()->json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
    }


    /**
     * 新增地址/更新地址
     * @param Request $request
     * @param string $model
     * @param string $id
     * @return bool|string
     */
    protected function add_address(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'city' => 'required|string',
                'district' => 'required|string',
                'address' => 'required|string|min:5|max:25',
                'phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:member_address,phone,'.$request->user_id.',user_id',

            ], [
                'name.required' => '名称不能为空',
                'address.required' => '地址不能为空',
                'phone.required' => '电话不能为空',
            ],
                [
                    'name' => '姓名',
                    'address' => '详细地址',
                    'phone' => '电话',

                ]
            );
            if ($validator->fails()) {//异常处理
                $errors = implode(' 和 ',$validator->messages()->all());
                throw new \Exception($errors);
            }
            $data = $request->except('key');
            $data['user_id'] = $request->user_id;
            if($request->id) {
                MemberAddress::where('id',$request->id)->update($data);
            }else{
                MemberAddress::create($data);
            }

            return Response()->json(['code'=>200,'msg'=>'发布成功']);
        }catch(\Exception $e){
            return Response()->json(['code'=>400,'msg'=>$e->getMessage()]);
        }

    }

    /**
     * 删除地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request){

        //检查地址信息
        if(MemberAddress::where(['user_id'=>$request->user_id,'id'=>$request->id])->exists() ==false){
            return Response()->json(['code'=>400,'msg'=>'地址信息异常']);
        }
        if(MemberAddress::destroy($request->id)){
            return Response()->json(['code'=>200,'msg'=>'删除成功']);
        }else{
            return Response()->json(['code'=>400,'msg'=>'删除']);
        }

    }

}
