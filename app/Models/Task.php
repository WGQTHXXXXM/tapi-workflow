<?php

namespace App\Models;

//任务
class Task extends BaseModel
{
    protected $fillable = ['name','instance_id','tpl_task_id','status','attributes','result','remark'];


    //准备中(ready), 开始(start)，结束(end)
    const STATUS_READY = 'ready';
    const STATUS_START = 'start';
    const STATUS_END = 'end';

    public function getAttributesAttribute($value)
    {
        return json_decode($value);
    }


    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = json_encode($value);
    }


    public function instance()
    {
        return $this->hasOne(Instance::class,'id','instance_id');
    }

    public function tplTask(){

        return $this->hasOne(TplTask::class,'id','tpl_task_id');
    }

    public function participants(){
        return $this->hasMany(Participant::class,'task_id','id');

    }
}
