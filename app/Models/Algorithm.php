<?php

namespace App\Models;

//算法
class Algorithm extends BaseModel
{
    protected $fillable = ['name', 'class_name', 'results', 'remark'];
    protected $visible = ['id', 'name', 'class_name', 'results', 'remark'];

    protected $appends = ['selects_objects'];


    public function getResultsAttribute($value)
    {
        return json_decode($value);
    }


    public function setResultsAttribute($value)
    {
        $this->attributes['results'] = json_encode($value);
    }

    public function getSelectsObjectsAttribute()
    {

        return  Select::whereIn('key',$this->results)->get();
    }

}
