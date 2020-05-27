<?php

namespace App\Http\Requests\Template;

use App\Http\Requests\BaseRequest;
use App\Models\TplLine;

class UpdateTemplateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //模板如果是锁定状态禁止创建
        $tplId = TplLine::where('id', $this->route('id'))->value('tpl_id');
        $this->checkLock($tplId);
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
            'name' => 'between:3,50|unique:templates',
            'ui_id' => 'exists:tpl_uis,id',
            'end_ui_id' => 'exists:tpl_uis,id',
            'init_x' => 'numeric',
            'init_y' => 'numeric',
            'end_x' => 'numeric',
            'end_y' => 'numeric'

        ];
    }


    public function messages()
    {
        return [
            'name.between' => '模板名称在3-50个字符之间',
            'name.unique' => '模板名称已经存在',
            'ui_id.exists' => '初始化UI模板不存在',
            'end_ui_id.string' => '结束UI模板不存在',
        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'ui_id' => 10002,
            'init_x' => 10003,
            'init_y' => 10004,
            'end_x' => 10005,
            'end_y' => 10006,
            'end_ui_id' => 10007
        ];
    }
}
