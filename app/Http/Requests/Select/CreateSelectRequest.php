<?php

namespace App\Http\Requests\Select;

use App\Http\Requests\BaseRequest;
use App\Models\Select;
use App\Models\TplLine;

class CreateSelectRequest extends BaseRequest
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
            'name' => 'required|between:1,50|unique:selects',
            'color' => [
                'required',
                'regex:/^#[0-9a-fA-F]{6}$/',
            ],
            'sort' => 'numeric|unique:selects',
            'key' => 'required|between:2,20|unique:selects',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => '选择器名称不能为空',
            'name.between' => '选择器名称在1-50个字符之间',
            'name.unique' => '选择器名称已经存在',
            'color.required' => '颜色必须填写',
            'color.regex' => '颜色值不对：如(#2233FF)',
            'sort.numeric' => '排序必须是数字',
            'sort.unique' => '排序数已经存在',
            'key.required' => '关键字key不能为空',
            'key.between' => '关键字范围2-20个字符',
            'key.unique' => '关键字已经存在',
        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'color' => 10002,
            'sort' => 10003,
            'key' => 10004,
        ];
    }
}
