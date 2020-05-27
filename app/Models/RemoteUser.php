<?php

namespace App\Models;



use App\Models\Abstracts\RestApiModelWithConsul;


//第三方服务 用户中心
class RemoteUser extends RestApiModelWithConsul
{


    protected static $service_name = 'user-center-service';


    protected static $apiMap = [
        'getInfo' => ['method' => 'GET', 'path' => 'user/guid/:id'],       //获取用户信息
    ];

    public static function getInfo($id)
    {
        $params = [':id' => $id];
        return static::getItem('getInfo', $params);
    }

}