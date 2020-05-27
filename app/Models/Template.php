<?php

namespace App\Models;

//模板
class Template extends BaseModel
{
    //
    protected $fillable = ['name', 'ui_id', 'end_ui_id', 'init_x', 'init_y', 'end_x', 'end_y', 'remark'];


    //获取相关线程
    public function tplLines()
    {
        return $this->hasMany(TplLine::class, 'tpl_id', 'id');

    }

    //获取决策模型
    public function tplDecisions()
    {
        return $this->hasMany(TplDecision::class, 'tpl_id', 'id');
    }


    //获取任务模型
    public function tplTasks()
    {
        return $this->hasMany(TplTask::class, 'tpl_id', 'id');

    }

    //获取模板UI
    public function tplUi()
    {
        return $this->hasOne(TplUi::class, 'id', 'ui_id');
    }

    //获取模板UI
    public function endTplUi()
    {
        return $this->hasOne(TplUi::class, 'id', 'end_ui_id');
    }


}
