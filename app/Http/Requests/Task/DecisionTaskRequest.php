<?php

namespace App\Http\Requests\Instance;


use App\Http\Requests\BaseRequest;

class DecisionTaskRequest extends BaseRequest
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
            "user_id"=>'required|string|alpha_num|max:36',
            "content"=>'required|string',
            "select_key"=>'required|exists:selects,key',
        ];
    }

    public function messages
    ()
    {
        return [
        ];
    }

    public function errorCode(): array
    {
        return [
            'user_id' => 10001,
            'content' => 10002,
            'select_key' => 10003,
        ];

    }

}
