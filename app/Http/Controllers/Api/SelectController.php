<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Select\CreateSelectRequest;
use App\Models\Select;
use Dingo\Api\Http\Request;

class SelectController extends ApiController
{

    public function index(Request $request)
    {

        $a = Select::all();

        return $this->responseCollection($a);
    }


    public function store(CreateSelectRequest $request)
    {
        $s = new Select($request->all());

        if(!$s->sort){
            $sort = Select::max('sort');
            $s->sort = $sort+1;
        }
        $s->save();

        return $this->responseCreated($s);
    }

}