<?php

namespace App\Http\Requests\Instance;


use App\Http\Requests\BaseRequest;

class CreateInstanceRequest extends BaseRequest
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
            'name' => 'required|between:3,50|unique:instances',
            'tpl_id' => 'required|exists:templates,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '实例名称不能为空',
            'name.unique' => '实例名称已经存在',
            'name.between' => '实例名称在3-50个字符之间',
            'tpl_id.required' => '模板id不能为空',
            'tpl_id.exists' => '模板id不存在',

        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'tpl_id' => 10002,
        ];

    }

}
