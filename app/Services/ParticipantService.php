<?php

namespace App\Services;

use App\Exceptions\ClientHttpException;
use App\Http\Requests\Task\AddParticipantRequest;
use App\Models\Algorithm;
use App\Models\Participant;
use App\Models\Rbac;
use App\Models\Record;
use App\Models\Task;
use App\Models\TplLine;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Exception\LogicException;

class ParticipantService
{

    /**
     * 根据传进来的用户确定是哪行participants数据
     */
    public function getPtcByUser($userId,$task)
    {
        $data = Participant::where(['key_id'=>$userId,'task_id'=>$task->id])->first();

        if(!empty($data)){
            return $data;
        }else{
            $roleIds = Participant::where(['task_id'=>$task->id,'type'=>Participant::TYPE_GROUP])->pluck('key_id');

            $roleUsers = app(Rbac::class)->getUsersByRoleIds($roleIds->toArray());


            foreach ($roleUsers->toArray() as $item) {
                if ($item['userId'] === $userId){
                    return Participant::where(['task_id'=>$task->id,'key_id'=>$item['roleId']])->first();
                }
            }

        }
        throw new ClientHttpException('传入的审批人不在任务的参与者里',2000);
    }

}