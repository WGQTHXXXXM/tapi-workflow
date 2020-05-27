<?php

namespace App\Http\Requests\Template;


use App\Http\Requests\BaseRequest;

class CreateTemplateRequest extends BaseRequest
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
            'name' => 'required|between:3,50|unique:templates',
            'ui_id' => 'required|exists:tpl_uis,id',
            'end_ui_id' => 'required|exists:tpl_uis,id',
            'init_x' => 'numeric',
            'init_y' => 'numeric',
            //
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '模板名称不能为空',
            'name.between' => '模板名称在3-50个字符之间',
            'name.unique' => '模板名称已经存在',
            'ui_id.required' => '开始UI模板不能为空',
            'ui_id.exists' => '开始UI模板不存在',
            'end_ui_id.required' => '结束UI模板不能为空',
            'end_ui_id.exists' => '结束UI模板不存在',
            'init_x.required' => '开始节点坐标x不存在',
            'init_y.required' => '开始节点坐标y不存在',
        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'ui_id' => 10002,
            'init_x' => 10003,
            'init_y' => 10004,
            'end_ui_id' => 10005,

        ];
    }

}
