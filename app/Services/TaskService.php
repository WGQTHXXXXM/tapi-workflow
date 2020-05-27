<?php


namespace App\Services;


use App\Exceptions\ClientHttpException;
use App\Http\Requests\Task\AddParticipantRequest;
use App\Models\AuthUser;
use App\Models\Instance;
use App\Models\Participant;
use App\Models\Rbac;
use App\Models\Record;
use App\Models\Select;
use App\Models\Task;
use App\Models\TplLine;
use Illuminate\Support\Facades\DB;

class TaskService
{


    //实例添加参与者
    public function addParticipants($taskId, $data)
    {

        //判断实例中是否有此任务
        $t = Task::find($taskId);
        if (!$t) {
            throw new ClientHttpException("此实例中，没有此任务了", 2000);
        }

        if ($t->status == Task::STATUS_END) {
            throw new ClientHttpException("此任务已经结束，不可以再撩了哦！", 2011);
        }

        if ($t->instance->end_time) {
            throw new ClientHttpException("实例已经结束，无法添加参与者", 2012);
        }

        //判断是否已经有此参与者
        $p = Participant::where('key_id', $data['key_id'])->where('task_id', $taskId)->count();
        if ($p) {
            throw new ClientHttpException("任务已经添加过此参与者了", 3000);
        }


        //如果是角色，判断下角色是否为空
        if ($data['type'] == Participant::TYPE_GROUP) {
            $users = app(Rbac::class)->roleUserIndex($data['key_id']);
            if ($users->count() == 0) {
                throw new ClientHttpException("角色内无可用用户，请重新选择", 10001);
            }

            //验证单独的用户是否已经添加过
            $p = Participant::where('task_id',$taskId)->whereIn('key_id',$users->pluck('userId')->toArray())->first();
            if($p){
                throw new ClientHttpException("添加角色失败，用户：{$p->name}  已经存在",10002);
            }
            
        }else{
            // 如果是个人，就验证下已添加角色是否还包含已经添加的
            //验证这个任务里已经有的参与者是否包含这个人，若包含报错
            $parS = Participant::where(['task_id' => $taskId, 'type' => Participant::TYPE_GROUP])->get();

            if ($parS->count()) {
                $userRoles = app(Rbac::class)->getUsersByRoleIds($parS->pluck('key_id')->toArray());
                if (in_array($data['key_id'], $userRoles->pluck('userId')->toArray())) {
                    throw new ClientHttpException("任务已经添加过此参与者了,存在其他角色中", 3001);
                }
            }
        }




        //写入参与者
        $participant = new Participant($data);
        $participant->instance_id = $t->instance_id;
        $participant->task_id = $taskId;
        $participant->save();

        return $participant;
    }



    // 把任务设置成启动状态
    public
    function startTask(Task $task)
    {
        $task->status = Task::STATUS_START;
        $task->save();

        // 把决策者的决定都设置为无效
        Record::where('task_id', $task->id)->update(['status' => false]);
    }


    // 决策实例
    public
    function decision($request, $taskId)
    {

        //检查任务是否存在
        $curTask = Task::find($taskId);
        if (!$curTask) {
            throw new ClientHttpException("任务不存在", 1000);
        }

        //判断任务是否在运行中
        if ($curTask->status != Task::STATUS_START) {
            throw new ClientHttpException("任务没有运行", 1001);
        }

        // 判断实例是否结束，是否开始
        $ins = Instance::find($curTask->instance_id);
        if (!$ins) {
            throw new ClientHttpException("实例不存在", 2001);
        }

        if (!$ins->start_time) {
            throw new ClientHttpException("实例未启动", 2002);
        }

        if ($ins->end_time) {
            throw new ClientHttpException("实例已经结束", 2002);
        }


        //检查选择器是否正确
        $select = Select::where('key', $request['select_key'])->first();
        if (!$select) {
            throw new ClientHttpException("选择器不存", 1002);
        }

        // 获取当前人是不是参与者
        $curPtc = app(ParticipantService::class)->getPtcByUser($request['user_id'], $curTask);//传入的参与者


        //判断是不是已经决策过了
        if ($select->type == Select::TYPE_DECISION){
            $c = Record::where([
                'status' => true,
                'task_id' => $taskId,
                'type' => Record::TYPE_DECISION,
                'participant_id' => $curPtc->id
            ])->count();
            if ($c) {
                throw new ClientHttpException("你已经做过决策", 1002);
            }
        }



        $res = DB::transaction(function () use ($request, $curTask, $curPtc, $select) {

            $user = AuthUser::retrieveById($request['user_id']);        //获取token的用户


            app(RecordService::class)->createRecord($curTask, $curPtc, $request, $select, $user);//创建记录

            // 如果不是决策操作就结束
            if ($select->type == Select::TYPE_FEEDBACK) {
                return [];
            }


            //寻找下一个节点集合
            $nextLines = $curTask->tplTask->nextLines;

            //通知下一个节点发生决策
            foreach ($nextLines as $line) {
                switch ($line->next_type) {
                    case TplLine::TYPE_TASK:
                        return app(NoticeService::class)->noticeTask($curTask, $line->next_id);
                        break;
                    case TplLine::TYPE_DECISION:
                        return app(NoticeService::class)->noticeDecision($curTask, $line->next_id);
                        break;
                    case TplLine::TYPE_END;
                        app(NoticeService::class)->noticeEnd($curTask, $line->next_id);
                        break;
                    default :
                        throw new ClientHttpException('存在错误的下一个节点：' . $line->next_type, 5000);
                }
            }
            return [];
        });
        return $res;
    }


    // 检查任务是不是都决策了
    public
    function isTaskAllDecisions(Task $task)
    {
        //检查是不是所有参与者都决策了

        $participantIds = $task->participants->pluck('id')->all();

        // 获取决策过的参与者
        $recordParticipantIds = Record::where([
            'status' => true,
            'task_id' => $task->id,
            'type' => Record::TYPE_DECISION,
        ])->pluck('participant_id')->toArray();


        foreach ($participantIds as $parId) {
            if (in_array($parId, $recordParticipantIds) == false) {
                return false;
            }

        }

        return true;
    }

    //根据模板id和实例id获取task
    public
    function getTaskByTpl($insId, $tplTaskId)
    {
        $task = Task::where('tpl_task_id', $tplTaskId)->where('instance_id', $insId)->first();
        if (!$task) {
            throw new ClientHttpException('任务不存在', 3000);
        }
        return $task;
    }


}