<?php

namespace App\Models;


//实例
class Instance extends BaseModel
{

    protected $fillable = ['name', 'tpl_id', 'remark', 'attributes'];


    public function getAttributesAttribute($value)
    {
        return json_decode($value);
    }


    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = json_encode($value);
    }

    public function template()
    {

        return $this->hasOne(Template::class, 'id', 'tpl_id');
    }


    //获取决策集合
    public function decisions()
    {
        return $this->hasMany(Decision::class, 'instance_id', 'id');
    }


    //获取任务集合
    public function tasks()
    {
        return $this->hasMany(Task::class, 'instance_id', 'id');
    }

    //获取任务集合
    public function curtasks()
    {
        return $this->hasMany(Task::class, 'instance_id', 'id');
    }


    // 获取进行中的任务
    public function scopeWithCurtasks($query)
    {
        return $query->with(["curtasks" => function ($query) {
            $query->where(['status' => Task::STATUS_START])->with(['participants.records','tplTask.tplUi','tplTask.selects']);
        }]);
    }

    // 获取实例所有相关信息
    public function scopeWithFull($query)
    {
        return $query->with(['template.endTplUi','template.tplUi', 'decisions.tplDecision.algorithm','tasks.tplTask.selects','tasks.participants.records','tasks.tplTask.tplUi']);
    }

}
