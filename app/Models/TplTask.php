<?php

namespace App\Models;

//模板任务
class TplTask extends BaseModel
{
    protected $fillable = ['name', 'tpl_id', 'ui_id', 'position_x', 'position_y','width','height', 'remark'];


    //显示选项集
    public function selects()
    {
        return $this->belongsToMany(Select::class, 'task_selects', 'tpl_task_id', 'select_id');
    }

    //获取模板UI
    public function tplUi(){
        return $this->hasOne(TplUi::class, 'id', 'ui_id');
    }

    //下一个节点(多个）
    public function nextLines()
    {
        return $this->hasMany(TplLine::class, 'last_id', 'id');

    }
}
