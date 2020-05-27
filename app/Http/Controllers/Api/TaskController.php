<?php


namespace App\Http\Controllers\Api;


use App\Exceptions\ClientHttpException;
use App\Http\Requests\Instance\DecisionTaskRequest;
use App\Http\Requests\Task\AddParticipantRequest;
use App\Models\Instance;
use App\Models\Participant;
use App\Models\Task;
use App\Models\TplLine;
use App\Services\InstanceService;
use App\Services\TaskService;
use Dingo\Api\Http\Request;

class TaskController extends ApiController
{


    //添加参与对象
    public function addParticipant($taskId, AddParticipantRequest $request, TaskService $service)
    {
        $d = $service->addParticipants($taskId, $request->all());
        return $this->responseCreated($d);

    }

    //删除参与对象
    public function removeParticipant($participantId)
    {
        Participant::destroy($participantId);

        return $this->responseItem([]);

    }

    //显示任务的所有参与者
    public function showParticipants($taskId)
    {
        $task = Task::find($taskId);
        if (!$task) {
            throw  new ClientHttpException("任务不存在", 1000);
        }

        return $this->responseCollection($task->participants);

    }


    //更新扩展字段
    public function updateAttributes($taskId, Request $request)
    {
        $task = Task::find($taskId);
        if (!$task) {
            throw  new ClientHttpException("任务不存在", 1000);
        }

        $task->attributes = $request->all();

        $task->save();

        return $this->responseUpdate($task);
    }

    public function show($id)
    {
        $task = Task::with(['instance','tplTask','participants.records'])->find($id);
        if(!$task){
            throw new ClientHttpException('任务不存在',1000);
        }

        return $this->responseItem($task);

    }

    //决策实例
    public function decision($id,DecisionTaskRequest $request, TaskService $service)
    {
        return $this->responseItem($service->decision($request->all(), $id));
    }


}