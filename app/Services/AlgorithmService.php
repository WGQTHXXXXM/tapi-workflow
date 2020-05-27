<?php

namespace App\Services;

use App\Models\Decision;
use App\Models\Task;

class AlgorithmService
{

    // 计算
    public function do(Task $task, Decision $decision)
    {
        $ns = 'App\\Algorithms\\';
        $className =$ns. $decision->tplDecision->algorithm->class_name;;

        $nextLine = (new $className($task,$decision))->compute();
        return $nextLine;
    }
}