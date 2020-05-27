<?php


namespace App\Http\Controllers\Api;


use App\Models\TplUi;
use Dingo\Api\Http\Request;

class TplUiController extends ApiController
{

    public function index(Request $request)
    {

        $a = TplUi::all();

        return $this->responseCollection($a);
    }


}