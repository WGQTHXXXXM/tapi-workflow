<?php

namespace App\Http\Requests\Instance;


use App\Http\Requests\BaseRequest;

class GetInstanceByIdsRequest extends BaseRequest
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
            'ids' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'ids.array' => '必须是数组',
        ];
    }

    public function errorCode(): array
    {
        return [
            'ids' => 10001,
        ];

    }

}
