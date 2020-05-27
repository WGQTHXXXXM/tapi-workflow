<?php


namespace App\Services;


use App\Exceptions\ClientHttpException;
use App\Exceptions\ServerHttpException;
use App\Http\Requests\Instance\CreateInstanceRequest;
use App\Models\AuthUser;
use App\Models\Decision;
use App\Models\Instance;
use App\Models\Participant;
use App\Models\Record;
use App\Models\Task;
use App\Models\Template;
use App\Models\TplLine;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Exception\LogicException;

class InstanceService
{

    /**
     * 创建实例，并且复制任务，和决策
     * @param CreateInstanceRequest $request
     * @return Instance
     */
    public function create(CreateInstanceRequest $request)
    {

        //检查模板是否锁定
        $tpl = Template::find($request->get('tpl_id'));
        if ($tpl->lock == false) {
            throw new ClientHttpException("模板状态不是锁定，无法创建实例", 2000);
        }

        try {
            DB::beginTransaction();

            //创建实例
            $d = $request->all();
            $ins = new Instance($d);
            $ins->save();

            $ins->template->load(['tplTasks.selects', 'tplTasks.tplUi', 'tplUi', 'tplDecisions', 'tplLines']);

            //复制task
            $tplTasks = $ins->template->tplTasks;
            foreach ($tplTasks as $tplTask) {
                $task = new Task();

                $task->name = $tplTask->name;
                $task->instance_id = $ins->id;
                $task->tpl_task_id = $tplTask->id;
                $task->status = Task::STATUS_READY;
                $task->save();
            }


            //复制决策
            $tplDecisions = $ins->template->tplDecisions;
            foreach ($tplDecisions as $tplDecision) {
                $decision = new Decision();

                $decision->name = $tplDecision->name;
                $decision->instance_id = $ins->id;
                $decision->tpl_decision_id = $tplDecision->id;
                $decision->save();
            }


            DB::commit();
            return $ins;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw new ServerHttpException("创建实例失败:" . $exception->getMessage());
        }


    }

    /**
     * 启动实例
     */
    public function launch($insId, $params)
    {

        //判断是否启动
        $ins = Instance::find($insId);
        if (!$ins) {
            throw new ClientHttpException('实例不存在:' . $insId, 10001);
        }

        if($ins->start_time){
            throw new ClientHttpException('实例已经启动，请勿重复启动' , 10002);
        }



        try {
            DB::beginTransaction();

            $ins->fill($params);
            $ins->start_time = nowTimeMs();
            $ins->created_by = $params['user_id'];          //发起人
            $ins->save();

            //更新最近的一个任务为运行状态
            $initLines = $ins->template->tplLines()->where(['last_type' => TplLine::TYPE_INIT])->get();
            foreach ($initLines as $line) {
                $task = app(TaskService::class)->getTaskByTpl($insId,$line->next_id);
                app(TaskService::class)->startTask($task);
            }


            $user = AuthUser::retrieveById($params['user_id']);

            if (!$user) {
                throw new ClientHttpException("操作用户不存在", 10000);
            }

            //给每一个任务都添加一个发起人观察者
//            $ins->tasks->map(function ($task,$key) use ($user){
//                $data = [
//                    'task_id' => $task->id,
//                    'instance_id' => $task->instance_id,
//                    'name' => $user->name,
//                    'key_id' => $user->id,
//                    'type' => Participant::TYPE_INDIVIDUAL,
//                    'code' => Participant::MY_CREATE,
//                ];
//                Participant::create($data);
//            });


            //增加记录
            Record::create([
                'instance_id' => $insId,
                'content' => $params['content'],
                'user_id' => $user->id,
                'user_name' => $user->name,
                'type' => Record::TYPE_CREATE,
                'select_key' => $params['select_key'],
                'status'=>true,
            ]);

            DB::commit();

            return $ins;
        } catch (LogicException $e) {
            DB::rollBack();
            throw new ClientHttpException("启动实例失败:" . $e->getMessage(), 10002);
        }
    }


    // 删除实例
    public function delete($id)
    {
        try {

            DB::beginTransaction();

            // 删除任务
            Task::where('instance_id', $id)->delete();

            // 删除决策
            Decision::where('instance_id', $id)->delete();

            // 删除日志
            Record::where('instance_id', $id)->delete();

            // 删除参与者
            Participant::where('instance_id', $id)->delete();

            // 删除实例
            Instance::destroy($id);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ServerHttpException("删除实例失败:" . $exception->getMessage());
        }

    }

    /**
     * 返回实例运行到哪里
     */
    public function curTask($line, $insId)
    {
        if ($line->next_type == TplLine::TYPE_TASK) {
            $tasks = Task::where(['tpl_task_id' => $line->next_id, 'instance_id' => $insId])->get();
            foreach ($tasks as $task) {
                $task->update(['status' => Task::STATUS_START]);
                Record::where(['task_id' => $task->id])->update(['status' => false]);
            }
        } elseif ($line->next_type == TplLine::TYPE_END) {
            Instance::where(['id' => $insId])->update(['end_time' => time()]);
        } else {
            throw new ClientHttpException("创建的模板，没有可start的任务", 2000);
        }
//        $nextLines = $line->next();
//        foreach ($nextLines as $nextLine){
//            if($nextLine->next_type == TplLine::TYPE_DECISION){//如果下一个是决策块，就把这个任务变成start
//                Task::where(['tpl_task_id'=>$nextLine->last_id,'instance_id'=>$insId])->update(['status'=>Task::STATUS_START]);
//                return;
//            }else{//否则算通过
//                Task::where(['tpl_task_id'=>$nextLine->last_id,'instance_id'=>$insId])->update(['status'=>Task::STATUS_END]);
//                $this->curTask($nextLine,$insId);
//            }
//        }
    }


    /**返回实例的start任务
     * @param $id
     * @return mixed
     */
    public function curInsTaskStart($id)
    {
        return Task::where(['instance_id' => $id, 'status' => Task::STATUS_START])->with('tplTask.selects')->get();
    }

    public function getInstanceByIds($params)
    {
        return Instance::whereIn('id', $params['ids'])->withFull()->withCurtasks()->get()->keyBy('id');
    }

}