<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;

//结果选择器
class Select extends BaseModel
{
    protected $fillable = ['name', 'color', 'remark', 'type', 'key', 'sort'];

    protected $visible = ['id', 'name', 'color', 'remark', 'type', 'key', 'sort'];

    const TYPE_FEEDBACK = 'feedback';
    const TYPE_CREATE = 'create';
    const TYPE_DECISION = 'decision';


    public static function boot()
    {


        static::addGlobalScope('orderBySort', function (Builder $builder) {
            $builder->orderBy('sort', 'asc');
        });


        parent::boot();

        static::withoutGlobalScope('orderByCreated')->get();
    }

}
