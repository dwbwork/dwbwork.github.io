<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 验证失败处理
     *
     * @param object $validator
     * @throws Illuminate\Http\Exceptions\HttpResponseException
     */
    public function failedValidation($validator)
    {
        $error = $validator->errors()->first();
        // $allErrors = $validator->errors()->all(); 所有错误

        $response = response()->json([
            'code' => 1,
            'msg'  => $error,
            'data' => null,
        ]);

        throw new HttpResponseException($response);
    }
}
