<?php
/**
 * Created by IntelliJ IDEA.
 * User: xh
 * Date: 16/4/28
 * Time: 18:49
 */

namespace App\Transformers;

use Dingo\Api\Http\Request;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model as Eloquent;


class BaseTransformer extends TransformerAbstract
{
    /**
     * 显示指定字段
     * @param Eloquent $model
     * @return Eloquent
     */
    public function display(Eloquent $model)
    {
        $request = app(Request::class);
        if ($request->has('fields')) {
            $fields = explode(',', $request->get('fields'));
            return $model->makeVisible($fields);
        } else {
            return $model;
        }
    }


}
