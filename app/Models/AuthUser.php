<?php
/**
 * Created by PhpStorm.
 * User: weijinlong
 * Date: 2018/8/24
 * Time: 下午4:01
 */
namespace App\Models;

use App\Auth\Contracts\UserResolver;
use App\Exceptions\RestApiException;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Abstracts\RestApiModelWithConsul;

class AuthUser extends RestApiModelWithConsul implements Authenticatable, UserResolver
{
    protected static $service_name = 'user-center-service';

    protected $primaryKey = 'guid';

    public static $apiMap = [
        'getUserByGuid'  => ['method' => 'GET', 'path' => 'user/guid/:guid'],
        'getUserByToken' => ['method' => 'GET', 'path' => 'login/user/token'],
        'getUsers' => ['method' => 'POST', 'path' => 'users'],
    ];


    public static function getUsers(Array $ids)
    {
        $response = self::getItem('getUsers',[], [
            'ids' => $ids
        ]);
        return $response;
    }

    public static function retrieveById($id)
    {
        try {
            $response = self::getItem('getUserByGuid', [
                ':guid' => $id
            ]);
        } catch (RestApiException $e) {
            return null;
        }

        return $response;
    }

    /**
     * 获取用户信息 (by token)
     * @param string $token
     * @return User|null
     */
    public static function retrieveByToken($token)
    {
        try {
            $response = self::getItem('getUserByToken');
        } catch (RestApiException $e) {
            return null;
        }

        return $response;
    }


    // ==================== Start implement functions of Illuminate\Contracts\Auth\Authenticatable  ==================== //
    public function getAuthIdentifierName()
    {
        return $this->primaryKey;
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return '';
    }

    public function getRememberToken()
    {
        return '';
    }

    public function setRememberToken($value)
    {
        return true;
    }

    public function getRememberTokenName()
    {
        return '';
    }
    // ==================== End implement functions of Illuminate\Contracts\Auth\Authenticatable  ==================== //



}
