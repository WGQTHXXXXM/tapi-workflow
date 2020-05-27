<?php

namespace App\Models;

//参与者
class Participant extends BaseModel
{
    protected $fillable = ['name', 'key_id', 'type', 'code','task_id','instance_id'];

    protected $visible = ['id','name', 'key_id', 'type', 'code','task_id','instance_id','approve','records','approve'];

    protected $appends = ['approve'];

    const TYPE_INDIVIDUAL =  'individual';
    const TYPE_GROUP = 'group';

    const MY_FOLLOW = 'follow';     //观察者
    const MY_CREATE = 'create';     //创建者
    const MY_APPROVE = 'approve';   //决策者


    //获取参与者操作日志
    public function records()
    {
        return $this->hasMany(Record::class,'participant_id','id');
    }


    // 获取参与者的决策
    public function getApproveAttribute()
    {

        foreach ($this->records as $record) {
            if ($record->task_id == $this->task_id)
                if ($record->participant_id == $this->id)
                    if($record->status == true)
                        if($record->type == Record::TYPE_DECISION) {
                            return Select::where('key',$record->select_key)->first();
                        }

        }

        return null;

        // 性能太慢
//        $selectKey = Record::where([
//            'task_id' => $this->task_id,
////            'participant_id' => $this->id,
////            'status' => true,
////            'type' => Record::TYPE_DECISION
////        ])->value('select_key');
//
//        return $selectKey;
    }


}
