<?php

namespace App\Services;

use App\Exceptions\ClientHttpException;
use App\Models\Record;
use App\Models\Select;

class RecordService
{

    // 把审批存入记录
    public function createRecord($task, $curPtc, $request,Select $select, $user)
    {

        if ($request['select_key'] === Record::TYPE_DECISION) {
            //存审批记录
            $isPass = Record::where([
                'task_id' => $task->id,
                'participant_id' => $curPtc->id,
                'type' => Record::TYPE_DECISION,
                'status' => true])->first();
            if (!empty($isPass))
                throw new ClientHttpException('这个参与者已经审批过了', 20000);
        }


        $newRe = new Record();
        $newRe->fill([
            'instance_id' => $task->instance_id,
            'task_id' => $task->id,
            'participant_id' => $curPtc->id,
            'content' => $request['content'],
            'type' => $select->type,
            'select_key' => $select->key,
            'user_id' => $user->id,
            'user_name' => $user->name,
        ]);

        $newRe->status = true;
        $newRe->save();

        return $newRe;
    }

}