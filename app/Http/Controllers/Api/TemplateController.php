<?php


namespace App\Http\Controllers\Api;


use App\Exceptions\AuthorizeException;
use App\Exceptions\ClientHttpException;
use App\Http\Requests\SimpleRequest;
use App\Http\Requests\Template\CreateTplDecisionRequest;
use App\Http\Requests\Template\CreateTplLineRequest;
use App\Http\Requests\Template\CreateTplTaskRequest;
use App\Http\Requests\Template\CreateTemplateRequest;
use App\Http\Requests\Template\UpdateTplDecisionRequest;
use App\Http\Requests\Template\UpdateTemplateRequest;
use App\Http\Requests\Template\UpdateTplLineRequest;
use App\Http\Requests\Template\UpdateTplTaskRequest;
use App\Models\Template;
use App\Models\TplDecision;
use App\Models\TplLine;
use App\Models\TplTask;
use App\Services\TemplateService;

class TemplateController extends ApiController
{

    public function index()
    {

        $a = Template::all();

        return $this->responseCollection($a);
    }

    //更新结束任务坐标
    public function update($id , UpdateTemplateRequest $request ){
        $tpl = Template::find($id);
        if (!$tpl){
            throw new ClientHttpException("此模板不存在", 10001);
        }

        $tpl->fill($request->all());
        $tpl->save();
        return $this->responseItem($tpl);

    }

    public function store(CreateTemplateRequest $request)
    {
        $data = $request->all();

        $tpl = new Template($data);

        $tpl->save();

        return $this->responseCreated($tpl);
    }


    //删除模板
    public function delete($id)
    {
        app(TemplateService::class)->delete($id);

        return $this->responseNoContent();
    }


    //显示模板所有的依赖 任务 决策 线程
    public function show($id)
    {


        $tpl = Template::with(['tplUi', 'tplLines', 'tplDecisions.algorithm', 'tplTasks.selects', 'tplTasks.tplUi'])->find($id);

        if (!$tpl) {
            throw new ClientHttpException("此模板不存在", 10001);
        }


        return $this->responseItem($tpl);
    }


    //创建模板任务
    public function createTask($tplId, CreateTplTaskRequest $request)
    {

        $tplTask = app(TemplateService::class)->createTask($tplId, $request);

        return $this->responseCreated($tplTask);

    }

    //更新模板
    public function updateTask($id, UpdateTplTaskRequest $request){
        $tplTask = app(TemplateService::class)->updateTask($id, $request);

        return $this->responseUpdate($tplTask);
    }

    // 删除任务
    public function removeTask($id)
    {
        //判断是否锁定
        $tp = TplTask::find($id);
        if ($tp) {
            $lock = Template::where('id', $tp->tpl_id)->value('lock');
            if ($lock){
                throw new AuthorizeException("模板为冻结状态禁止删除",40001);
            }
        }else{
            throw new ClientHttpException("模板任务不存在",10001);
        }


        app(TemplateService::class)->removeTask($id);
        return $this->responseNoContent();
    }

    // 锁定模板
    public function lock($id, TemplateService $service)
    {
        $tpl = $service->lock($id);
        return $this->responseUpdate($tpl);

    }

    // 解锁
    public function unlock($id)
    {
        $tpl = app(TemplateService::class)->unlock($id);
        return $this->responseUpdate($tpl);

    }



    //创建模板决策
    public function createDecision($tplId, CreateTplDecisionRequest $request)
    {

        $tpl = Template::find($tplId);
        if (!$tpl) {
            throw new ClientHttpException("模板:{$tplId} 不存在", 10000);
        }

        //位置检查是否存在
        $tpl = TplDecision::where('tpl_id', $tplId)
            ->where('position_x', $request->get('position_x'))
            ->where('position_y', $request->get('position_y'))
            ->first();
        if ($tpl) {
            throw new ClientHttpException("当前决策的坐标上已经有元素了", 20000);
        }


        $tplDecision = new TplDecision($request->all());
        $tplDecision->tpl_id = $tplId;
        $tplDecision->save();


        return $this->responseCreated($tplDecision);

    }

    public function updateDecision($tplDecisionId, UpdateTplDecisionRequest $request)
    {

        $tplDecision = TplDecision::find($tplDecisionId);
        if (!$tplDecision) {
            throw new ClientHttpException("决策模板:{$tplDecisionId} 不存在", 10000);
        }

        $tplDecision->fill($request->all());

        $tplDecision->save();

        return $this->responseUpdate($tplDecision);

    }

    //删除模板决策
    public function removeDecision($tplDecisionId, SimpleRequest $request){
        //判断是否锁定
        $td = TplDecision::find($tplDecisionId);
        if ($td) {
            $request->checkLock($td->tpl_id);
        }else{
            throw new ClientHttpException("模板决策不存在",10001);
        }

        $td->delete();

        TplLine::where('last_id',$tplDecisionId)->orWhere('next_id',$tplDecisionId)->delete();

        return $this->responseNoContent();
    }


    //创建模板流程线
    public function createLine($tplId, CreateTplLineRequest $request)
    {


        $tplLine = app(TemplateService::class)->createLine($tplId, $request);

        return $this->responseCreated($tplLine);
    }

    //修改模板流程线
    public function updateLine($id, UpdateTplLineRequest $request)
    {

        //判断是否锁定
        $tl = TplLine::find($id);
        if ($tl) {
            $request->checkLock($tl->tpl_id);
        }else{
            throw new ClientHttpException("模板线程不存在",11001);
        }

        $tl->fill($request->all());
        $tl->save();

        return $this->responseUpdate($tl);
    }



    //删除模板流程线
    public function removeLine($id, SimpleRequest $request)
    {

        //判断是否锁定
        $tl = TplLine::find($id);
        if ($tl) {
            $request->checkLock($tl->tpl_id);
        }else{
            throw new ClientHttpException("模板线程不存在",11001);
        }

        $tl->delete();
        return $this->responseNoContent();
    }
}