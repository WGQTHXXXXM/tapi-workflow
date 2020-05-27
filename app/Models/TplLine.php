<?php

namespace App\Models;

//模板流程线
class TplLine extends BaseModel
{
    const TYPE_INIT = 'init';
    const TYPE_END = 'end';
    const TYPE_TASK = 'task';
    const TYPE_DECISION = 'decision';


    protected $fillable = ['name', 'tpl_id', 'last_id', 'last_type', 'last_anchor', 'next_id', 'next_type', 'next_anchor', 'remark'];



}
