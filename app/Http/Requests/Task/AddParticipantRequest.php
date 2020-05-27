<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseRequest;
use App\Models\Participant;

class AddParticipantRequest extends BaseRequest
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
            'name' => 'required|between:2,10',
            'key_id' => 'required',
            'type' => 'required|in:individual,group',
            'code' => 'required|in:'.Participant::MY_APPROVE.','.Participant::MY_CREATE.','.Participant::MY_FOLLOW,
        ];
    }


    public function messages()
    {
        return [
            'name.between' => '参与者名字长度在2-10个字符之间',
            'key_id.required' => 'key_id 不能为空',
            'type.required' => '参与者类型 不能为空',
            'type.in' => '参与者类型只能是：个人(individual) or 组织(group)',
            'code.required' => '相关类型 不能为空',
            'code.in' => '相关类型只能是：'.Participant::MY_APPROVE.','.Participant::MY_CREATE.','.Participant::MY_FOLLOW,

        ];
    }

    public function errorCode(): array
    {
        return [
            'name' => 10001,
            'key_id' => 10002,
            'type' => 10003,
            'code' => 10004,
        ];
    }
}
