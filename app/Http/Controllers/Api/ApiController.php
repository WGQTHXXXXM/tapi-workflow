<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\ResponseApiTrait;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    use Helpers,ResponseApiTrait;
}
