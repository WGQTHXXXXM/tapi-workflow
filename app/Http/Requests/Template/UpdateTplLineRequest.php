<?php

namespace App\Http\Requests\Template;

use App\Http\Requests\BaseRequest;
use App\Models\TplLine;

class UpdateTplLineRequest extends BaseRequest
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
            'name' => 'between:1,10',
            'last_type' => 'in:task,decision,init',
            'last_anchor' => 'numeric',

            'next_type' => 'in:task,decision,end,init',
            'next_anchor' => 'numeric',

        ];
    }


    public function messages()
    {
        return [
            'name.between' => '流程线名称在1-10个字符之间',
            'last_type.in' => '上一个节点类型错误，只能连接任务节点，或决策节点',
            'next_type.in' => '下一个节点类型错误，不能是初始节点',
        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'last_id' => 10002,
            'last_type' => 10003,
            'last_anchor' => 10004,
            'next_id' => 10005,
            'next_type' => 10006,
            'next_anchor' => 10007
        ];
    }
}
