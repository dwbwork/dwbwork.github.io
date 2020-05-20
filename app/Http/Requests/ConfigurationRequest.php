<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_id'  => 'required|string',
            'sort'  => 'required|numeric',
            'label' =>'required',
            'key' =>'required',
            'val' =>'required',
            'type' =>'required',
           
            
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => '配置组 必填',
            'sort'  => '排序 不能为空',
            'label' =>'配置项名称 不能为空',
            'key' =>'配置项字段 不能为空',
            'val' =>'配置项字段 不能为空',
            'type' =>'配置项类型 不能为空',
            
        ];
    }
}
