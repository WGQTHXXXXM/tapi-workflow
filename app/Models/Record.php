<?php

namespace App\Models;

//日志
class Record extends BaseModel
{
    //
    protected $fillable = ['instance_id', 'task_id', 'participant_id', 'user_id', 'user_name', 'content', 'type', 'status','select_key'];


    const TYPE_DECISION = 'decision'; //决策
    const TYPE_FEEDBACK = 'feedback'; //反馈
    const TYPE_CREATE = 'create'; //创建配置


    public function select()
    {
        return $this->hasOne(Select::class, 'key', 'select_key');
    }


    public function participant()
    {
        return $this->hasOne(Participant::class, 'id', 'participant_id');

    }
}
