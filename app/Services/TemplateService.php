<?php


namespace App\Services;


use App\Exceptions\ClientHttpException;
use App\Exceptions\ServerHttpException;
use App\Http\Requests\Template\CreateTplLineRequest;
use App\Http\Requests\Template\CreateTplTaskRequest;
use App\Http\Requests\Template\UpdateTplTaskRequest;
use App\Models\Instance;
use App\Models\Select;
use App\Models\TaskSelect;
use App\Models\Template;
use App\Models\TplDecision;
use App\Models\TplLine;
use App\Models\TplTask;
use Illuminate\Support\Facades\DB;

class TemplateService
{

    public function delete($id)
    {
        //判断是否有实例依赖
        $c = Instance::where('tpl_id', $id)->count();
        if ($c > 0) {
            throw new ClientHttpException("有{$c}个实例依赖此模板，无法删除", 1001);
        }

        DB::beginTransaction();

        try {
            //删除任务
            TplTask::where('tpl_id', $id)->delete();

            //删除决策
            TplDecision::where('tpl_id', $id)->delete();

            //删除流程线
            TplLine::where('tpl_id', $id)->delete();

            //删除自己
            Template::destroy($id);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();

            throw new ServerHttpException("删除模板失败:" . $exception->getMessage());
        }

    }


    //更新任务集
    public function updateTask($id, UpdateTplTaskRequest $request)
    {
        try {
            DB::beginTransaction();

            $tt = TplTask::find($id);
            if (!$tt) {
                throw new ClientHttpException("模板任务不存在", 11001);
            }

            $tt->fill($request->all());
            $tt->save();

            //如果有选项集，那么就更新
            if ($request->has('selects')) {


                $selectIds = TaskSelect::where('tpl_task_id', $id)->pluck('select_id')->toArray();
                foreach ($selectIds as $index => $selectId) {
                    //如果不存在就删除
                    if (in_array($selectId, $request->get('selects')) == false) {
                        TaskSelect::where('select_id', $selectId)->delete();
                        unset($selectIds[$index]);
                    }

                }

                foreach ($request->get('selects') as $selectId) {
                    if (!in_array($selectId, $selectIds)) {
                        TaskSelect::firstOrCreate([
                            'tpl_task_id' => $id,
                            'select_id' => $selectId
                        ]);
                    }
                }

                $tt->selects;
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            throw new ServerHttpException("创建实例失败:" . $exception->getMessage());
        }


        return $tt;

    }


    // 删除任务
    public function removeTask($id)
    {

        try {
            DB::beginTransaction();

            $tt = TplTask::find($id);
            if (!$tt) {
                throw new ClientHttpException("模板任务不存在", 11001);
            }
            $tt->delete();

            TaskSelect::where('tpl_task_id', $id)->delete();

            TplLine::where('last_id', $id)->orWhere('next_id', $id)->delete();


            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            throw new ServerHttpException("删除模板任务失败:" . $exception->getMessage());
        }


    }

    //创建任务
    public function createTask($tplId, CreateTplTaskRequest $request)
    {
        $tpl = Template::find($tplId);
        if (!$tpl) {
            throw new ClientHttpException("模板:{$tplId} 不存在", 10000);
        }

        //位置检查是否存在
        $tpl = TplTask::where('tpl_id', $tplId)
            ->where('position_x', $request->get('position_x'))
            ->where('position_y', $request->get('position_y'))
            ->first();
        if ($tpl) {
            throw new ClientHttpException("当前任务的坐标上已经有元素了", 20000);
        }

        DB::beginTransaction();

        try {
            //写入模板任务表
            $tplTask = new TplTask($request->all());
            $tplTask->tpl_id = $tplId;
            $tplTask->save();


            //写入选项级关联
            foreach ($request->get('selects') as $selectId) {
                $ts = new TaskSelect();
                $ts->tpl_task_id = $tplTask->id;
                $ts->select_id = $selectId;
                $ts->save();
            }

            DB::commit();

            return $tplTask;

        } catch (\Exception $exception) {
            DB::rollBack();

            throw new ServerHttpException("创建实例失败:" . $exception->getMessage());
        }


    }


    //创建流程线
    public function createLine($tplId, CreateTplLineRequest $request)
    {

        $tpl = Template::find($tplId);
        if (!$tpl) {
            throw new ClientHttpException("模板:{$tplId} 不存在", 10000);
        }


        //判断是否存在
        $tplLine = TplLine::where('tpl_id', $tplId)->where('last_id', $request->last_id)->where('next_id', $request->next_id)->first();
        if ($tplLine) {
            throw new ClientHttpException("流程线已经存在", 11000);
        }


        //判断上一个节点是否存在
        if ($request->get('last_type') == TplLine::TYPE_TASK) {
            $d = TplTask::find($request->get('last_id'));
            if (!$d) {
                throw new ClientHttpException("上一个节点id不存在", 11001);
            }
        } else if ($request->get('last_type') == TplLine::TYPE_DECISION) {
            {
                $d = TplDecision::find($request->get('last_id'));
                if (!$d) {
                    throw new ClientHttpException("上一个节点id不存在", 11002);
                }
            }
        } else if ($request->get('last_type') == TplLine::TYPE_INIT) {       //多一个初始化模板

        } else if ($request->get('last_type') == TplLine::TYPE_END) {       //多一个初始化模板
            throw new ClientHttpException("上一个节点类型不能为 结束节点", 11003);

        } else {
            throw new ClientHttpException("上一个节点类型错误", 11000);

        }

        //判断下一个节点是否存在
        if ($request->get('next_type') == TplLine::TYPE_TASK) {
            $d = TplTask::find($request->get('next_id'));
            if (!$d) {
                throw new ClientHttpException("下一个节点id不存在", 12001);
            }
        } else if ($request->get('next_type') == TplLine::TYPE_DECISION) {
            {
                $d = TplDecision::find($request->get('next_id'));
                if (!$d) {
                    throw new ClientHttpException("下一个节点id不存在", 12002);
                }
            }
        } else if ($request->get('next_type') == TplLine::TYPE_END) {       //多一个初始化模板

        } else {
            throw new ClientHttpException("下一个节点类型错误", 12003);
        }


        //如果上一个节点是决策，下一个节点必须是任务
        if ($request->get('last_type') == TplLine::TYPE_DECISION && $request->get('next_type') == TplLine::TYPE_DECISION) {
            throw new ClientHttpException("下一个节点不能是决策节点", 12004);
        }


        $tplLine = new TplLine($request->all());
        $tplLine->tpl_id = $tplId;
        $tplLine->save();


        return $tplLine;

    }


    //锁定模板
    public function lock($tplId)
    {
        //寻找init线
        $intLine = TplLine::where('tpl_id', $tplId)->where('last_type', TplLine::TYPE_INIT)->first();
        if (!$intLine) {
            throw new ClientHttpException("模板不完整，缺少初始流程线", 4001);
        }

        //寻找end线
        $intLine = TplLine::where('tpl_id', $tplId)->where('next_type', TplLine::TYPE_END)->first();
        if (!$intLine) {
            throw new ClientHttpException("模板不完整，缺少结束流程线", 4002);
        }


        $tpl = Template::with(['tplTasks.nextLines','tplDecisions.nextLines','tplDecisions.lastLines'])->find($tplId);

        if(!$tpl->ui_id){
            throw new ClientHttpException("模板不完整，缺少初始化界面", 4011);
        }
        if(!$tpl->end_ui_id){
            throw new ClientHttpException("模板不完整，缺少结束界面", 4012);
        }

        //检查任务是否都有关联
        foreach ($tpl->tplTasks as $tplTask) {
            if (count($tplTask->nextLines) === 0) {
                throw new ClientHttpException("模板不完整，任务模板[{$tplTask->name}]缺少流程线连接", 4003);
            }
        }

        //检查决策是否都有关联
        foreach ($tpl->tplDecisions as $tplDecision) {
            if (count($tplDecision->nextLines) === 0) {
                throw new ClientHttpException("模板不完整，决策模板[{$tplDecision->name}]缺少流程线连接", 4004);
            }

            if (count($tplDecision->lastLines) < 2) {
                throw new ClientHttpException("模板不完整，决策模板[{$tplDecision->name}]至少连接2条下一个任务节点", 4005);
            }

            //判断决策是否设置了条件关系
            if(!$tplDecision->select_result){
                throw new ClientHttpException("模板不完整，决策模板[{$tplDecision->name}]未设置结果路径", 4006);

            }

        }


        //
        $tpl->lock = true;
        $tpl->save();

        return $tpl;
    }

    //解锁模板
    public function unlock($tplId)
    {
        //检查是否有实例

        $ins = Instance::where('tpl_id', $tplId)->first();
        if ($ins) {
            throw new ClientHttpException("此模板有实例，不允许解锁", 1001);
        }


        $tpl = Template::find($tplId);
        if (!$tpl) {
            throw new ClientHttpException("模板不存在", 1202);
        }

        $tpl->lock = false;
        $tpl->save();


        return $tpl;
    }

}