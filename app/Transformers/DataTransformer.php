<?php
/**
 * Created by IntelliJ IDEA.
 * User: xh
 * Date: 16/4/28
 * Time: 18:49
 */

namespace App\Transformers;



class DataTransformer extends BaseTransformer
{
    public function transform($data)
    {

        return (array)$data;
    }

}