<?php


namespace App\Algorithms;


use App\Models\Record;
use App\Models\Select;
use App\Models\TplLine;
use App\Services\TaskService;


class AllYesAlgorithm extends BaseAlgorithms
{
    const RESULT_YES = 'YES';       //两种结果
    const RESULT_NO = 'NO';         //两种结果

    public function compute()
    {

        $isNotOK = Record::where([
            'status' => true,
            'task_id' => $this->getLastTask()->id,
            'type' => Select::TYPE_DECISION,
        ])->where('select_key', '<>', 'YES')->count();

        //如果有非yes的就进入NO的路
        if ($isNotOK) {
            $lineId = $this->getDecision()->tplDecision->select_result->{self::RESULT_NO};
            $line = TplLine::find($lineId);
            return  $line;
        }

        $isAlldecisions = app(TaskService::class)->isTaskAllDecisions($this->getLastTask());
        if($isAlldecisions){
            $lineId = $this->getDecision()->tplDecision->select_result->{self::RESULT_YES};
            $line = TplLine::find($lineId);
            return  $line;
        }

        return false;
    }

}