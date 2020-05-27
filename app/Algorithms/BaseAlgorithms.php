<?php


namespace App\Algorithms;


use App\Models\Decision;
use App\Models\Instance;
use App\Models\Select;
use App\Models\Task;
use App\Models\Template;
use App\Models\TplLine;

abstract class BaseAlgorithms
{
    private $task;
    private $decision;
    private $instance;


    public function __construct(Task $lastTask, Decision $decision)
    {
        $this->task = $lastTask;
        $this->decision = $decision;
        $this->instance = Instance::find($lastTask->instance_id);

    }

    protected function getLastTask()
    {
        return $this->task;
    }

    protected function getDecision()
    {
        return $this->decision;
    }

    protected function getInstance()
    {
        return $this->instance;
    }

    /**
     * /根据相关任务，计算出结果
     * @return TplLine | false
     */
    abstract public function compute();

}