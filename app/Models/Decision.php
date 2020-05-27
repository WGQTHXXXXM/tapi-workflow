<?php

namespace App\Models;

//决策
class Decision extends BaseModel
{
    protected $fillable = ['name', 'instance_id', 'tpl_decision_id','result' , 'remark'];


    public function tplDecision(){

        return $this->hasOne(TplDecision::class,'id','tpl_decision_id');
    }
}
