<?php


namespace App\Http\Controllers\Api;


use App\Exceptions\ClientHttpException;
use App\Http\Requests\Instance\CreateInstanceRequest;
use App\Http\Requests\Instance\GetInstanceByIdsRequest;
use App\Http\Requests\Instance\UpdateInstanceRequest;
use App\Models\Instance;
use App\Models\Participant;
use App\Models\Record;
use App\Services\InstanceService;
use Dingo\Api\Http\Request;

class InstanceController extends ApiController
{

    public function index(Request $request)
    {

        $a = Instance::withFull()->get();

        return $this->responseCollection($a);
    }

    public function store(CreateInstanceRequest $request, InstanceService $service)
    {

        $ins = $service->create($request);

        return $this->responseItem($ins);

    }

    public function update(UpdateInstanceRequest $request, InstanceService $service, $id)
    {

        $data = $service->launch($id, $request->all());
        return $this->responseUpdate($data);

    }


    //显示实例详情
    public function show($id)
    {



        $ins = Instance::withFull()->find($id);
        if (!$ins) {
            throw new ClientHttpException("此实例不存在", 10001);
        }

        $ins->lines = $ins->template->tplLines;


        unset($ins->template->tplLines);


        return $this->responseItem($ins->makeHidden('tpl_lines'));

    }


    // 删除实例
    public function delete($id)
    {
        app(InstanceService::class)->delete($id);
        return $this->responseItem([]);
    }

    /**
     * 返回实例start的任务
     */
    public function curInsTaskStart($id, InstanceService $insSer)
    {
        return $this->responseCollection($insSer->curInsTaskStart($id));
    }

    // 获取实例所有日志
    public function records($id)
    {
        $data = Record::where('instance_id', $id)->with(['select','participant'])->get();
        return $this->responseCollection($data);
    }

    /**
     * 通过ids批量查询实例
     */
    public function getInstanceInIds(GetInstanceByIdsRequest $request, InstanceService $service)
    {
        $data = $service->getInstanceByIds($request->all());
        return $this->responseCollection($data);
    }

}
