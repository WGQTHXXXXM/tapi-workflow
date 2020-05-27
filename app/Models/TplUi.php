<?php

namespace App\Models;


class TplUi extends BaseModel
{
    protected $fillable = ['name', 'component', 'remark'];
    protected $visible = ['id', 'name', 'component', 'remark'];

}
