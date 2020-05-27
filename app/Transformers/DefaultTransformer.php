<?php
/**
 * Created by IntelliJ IDEA.
 * User: xh
 * Date: 16/4/28
 * Time: 18:49
 */

namespace App\Transformers;


use App\Models\BaseModel;
use Dingo\Api\Http\Request;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model as Eloquent;

class DefaultTransformer extends BaseTransformer
{
    public function transform(Eloquent $model)
    {
        return $this->display($model)->toArray();
    }

}
