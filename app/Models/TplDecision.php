<?php

namespace App\Models;


//模板决策
class TplDecision extends BaseModel
{
    protected $fillable = ['name', 'tpl_id', 'algorithm_id','select_result','position_x','position_y', 'remark'];




    //计算因子
    public function algorithm(){
        return $this->hasOne(Algorithm::class,'id','algorithm_id');

    }

    //选项结果关系 :[{"选项id":"流程id"}]

    public function getSelectResultAttribute($value)
    {
        return json_decode($value);
    }


    public function setSelectResultAttribute($value)
    {
        $this->attributes['select_result'] = json_encode($value);
    }

    //下一个节点(多个）
    public function nextLines()
    {
        return $this->hasMany(TplLine::class, 'next_id', 'id');

    }

    //上一个节点(多个）
    public function lastLines()
    {
        return $this->hasMany(TplLine::class, 'last_id', 'id');

    }

}
