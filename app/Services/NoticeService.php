<?php

namespace App\Services;


use App\Exceptions\ClientHttpException;
use App\Exceptions\ServerHttpException;
use App\Models\Decision;
use App\Models\Instance;
use App\Models\Participant;
use App\Models\Record;
use App\Models\Task;
use App\Models\TplLine;
use App\Models\TplTask;


/*
 * 通知服务，收到决策后通知其他节点
 */

class NoticeService
{

    // 如果下一个节点是结束节点
    public function noticeEnd(Task $lastTask, string $tplId)
    {
        $is = app(TaskService::class)->isTaskAllDecisions($lastTask);
        //如果还存在么有决策的参与者就跳过
        if (!$is) {
            return;
        }
        // 如果都通过，则修改lastTask为完成，
        $lastTask->status = Task::STATUS_END;
        $lastTask->save();


        $ins = Instance::find($lastTask->instance_id);
        $ins->end_time = nowTimeMs();
        $ins->save();

    }


    //决策模块接收结果
    public function noticeDecision(Task $lastTask, string $nextTplDecisionId)
    {

        $decision = Decision::where('tpl_decision_id', $nextTplDecisionId)
            ->where('instance_id', $lastTask->instance_id)
            ->with('tplDecision.algorithm')
            ->first();
        if (!$decision) {
            throw new ClientHttpException('决策不存在', 3000);
        }


        $nextLine = app(AlgorithmService::class)->do($lastTask, $decision);

        //判断是否有下一条路
        if (!$nextLine) {
            return [];
        }

        // 决策后面必然跟task
        switch ($nextLine->next_type){
            case TplLine::TYPE_TASK:
                $nextTask = app(TaskService::class)->getTaskByTpl($lastTask->instance_id,$nextLine->next_id);
                app(TaskService::class)->startTask($nextTask);

                $lastTask->status = Task::STATUS_END;
                $lastTask->save();
                return $nextTask;
                break;
            case TplLine::TYPE_END:
                $ins = Instance::find($lastTask->instance_id);
                $ins->end_time = nowTimeMs();
                $ins->save();

                $lastTask->status = Task::STATUS_END;
                $lastTask->save();
                return [];
                break;
            case TplLine::TYPE_DECISION:
            case TplLine::TYPE_INIT:
            default:
            throw new ServerHttpException('决策后面跟的类型异常:'.$nextLine->next_type,3999);
        }


    }


    //接受任务节点信息
    public function noticeTask(Task $lastTask, string $nextTplTaskId)
    {
        $lastTask->status = Task::STATUS_END;
        $lastTask->save();

        // nextTask为开始
        $nowTask = app(TaskService::class)->getTaskByTpl($lastTask->instance_id, $nextTplTaskId);
        app(TaskService::class)->startTask($nowTask);
        return $nowTask;
    }

}