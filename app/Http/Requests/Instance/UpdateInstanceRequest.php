<?php

namespace App\Http\Requests\Instance;


use App\Http\Requests\BaseRequest;

class UpdateInstanceRequest extends BaseRequest
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
            'start_time' => 'numeric',
            'user_id'=>'required|string|alpha_num|max:36',
            'content'=>'max:201',
            'select_key'=>'exists:selects,key',
            'attributes' => [
                function ($attribute, $value, $fail) {
                    if (is_object($value) == false && is_array($value) == false) {
                        $fail("attributes属性集必须是JSON格式");
                        return;
                    }
                },
            ]
        ];
    }

    public function messages()
    {
        return [
            'start_time.numeric' => '开始时间必须是毫秒时间戳',
            'user_id.required' => '发起人不能为空',
            'content.max' => '备注最多200个字符',
            'select_key.exists' => '选择器按钮不存在',
        ];
    }

    public function errorCode(): array
    {
        return [
            'start_time' => 10001,
            'user_id' => 10002,
            'content' => 10003,
            'select_key' => 10004,
            'attributes' => 10005,
        ];

    }

}
