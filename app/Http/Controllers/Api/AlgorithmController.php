<?php


namespace App\Http\Controllers\Api;


use App\Models\Algorithm;
use Dingo\Api\Http\Request;

class AlgorithmController extends ApiController
{

    public function index(Request $request)
    {

        $a = Algorithm::all();
        $a->makeVisible('selects_objects');

        return $this->responseCollection($a);
    }



}