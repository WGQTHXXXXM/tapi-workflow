<?php

namespace App\Http\Requests\Template;


use App\Http\Requests\BaseRequest;
use App\Models\Select;
use App\Models\TplTask;

class UpdateTplTaskRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //模板如果是锁定状态禁止创建
        $tplId = TplTask::where('id', $this->route('id'))->value('tpl_id');
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
            'name' => 'between:2,50',
            'ui_id' => 'exists:tpl_uis,id',
            'position_x' => 'numeric',
            'position_y' => 'numeric',
            'width' => 'numeric',
            'height' => 'numeric',
            'selects' => [
                'array',
                function ($attribute, $value, $fail) {
                    //验证是否有重复
                    if (count($value) != count(array_unique($value))) {
                        $fail("结果集不能有重复的元素");
                        return;
                    }

                    $count = Select::whereIn('id',$value)->count();

                    if ($count != count($value)){
                        $fail("结果集id不正确");
                        return;
                    }

                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.between' => '任务名称在2-50个字符之间',
            'ui_id.exists' => '界面id 不存在',
            'position_x.numeric' => '坐标x必须为数字',
            'position_y.numeric' => '坐标y必须为数字',
            'selects.array' => '选项集必须是数组',
            'selects.distinct' => '选项集数据不能有相同的',
            'width.required' => '宽度 不存在',
            'width.numeric' => '宽度必须为数字',
            'height.required' => '高度不存在',
            'height.numeric' => '高度必须为数字',


        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'ui_id' => 10002,
            'position_x' => 10003,
            'position_y' => 10004,
            'selects' => 10005,
            'width' => 10006,
            'height' => 10007,
        ];
    }

}
