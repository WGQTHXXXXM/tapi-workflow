<?php

namespace App\Http\Requests\Template;

use App\Http\Requests\BaseRequest;
use App\Models\Select;
use App\Models\Template;
use App\Models\TplDecision;
use App\Models\TplLine;

class UpdateTplDecisionRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //模板如果是锁定状态禁止创建

        $tplId = TplDecision::where('id', $this->route('id'))->value('tpl_id');
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
            'algorithm_id' => 'exists:algorithms,id',
            'position_x' => 'numeric',
            'position_y' => 'numeric',
            'select_result' => [
                'array',
                function ($attribute, $value, $fail) {

                    foreach ($value as $selectKey => $tplLineId ) {
                        $s = Select::where('key',$selectKey)->first();
                        if (!$s) {
                            $fail("选项结果集,选项：{$selectKey} 不存在");
                            return;
                        }

                        if($tplLineId){
                            $s = TplLine::find($tplLineId);
                            if (!$s) {
                                $fail("选项结果集，流程线：{$tplLineId} 不存在");
                                return;
                            }
                        }
                    }


                }
            ]
        ];
    }


    public function messages()
    {
        return [
            'name.required' => '决策名称不能为空',
            'name.between' => '决策名称在2-50个字符之间',
            'algorithm_id.required' => 'algorithm_id 不能为空',
            'algorithm_id.exists' => 'algorithm_id 不存在',
            'position_x.required' => '坐标x 不存在',
            'position_x.numeric' => '坐标x必须为数字',
            'position_y.required' => '坐标y不存在',
            'position_y.numeric' => '坐标y必须为数字',
            'select_result.array' => '选项结果集必须是json数组',


        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'algorithm_id' => 10002,
            'position_x' => 10003,
            'position_y' => 10004,
            'select_result' => 10005,
        ];
    }
}
