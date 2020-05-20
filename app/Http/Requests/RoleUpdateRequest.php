<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
            'name'=>'required|unique:roles,name,'.$this->id.',id|max:200',
            'display_name'  => 'required||regex:/\p{Han}/u'
        ];
    }

    public function messages()
    {
        return [
            'name.regex:/\p{Han}/u'  => '角色名 必须为汉字',
            'display_name.regex:/\p{Han}/u'  => '显示名称 必须为汉字',
            'display_name.required'  => '显示名称 不能为空'
        ];
    }
}
