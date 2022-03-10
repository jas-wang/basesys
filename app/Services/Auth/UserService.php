<?php

namespace App\Services\Auth;

use App\Models\Auth\User;
use App\Services\BaseService;

/**
 * 用户服务中间件
 */
class UserService extends BaseService
{
    /**
     * 根据用户名获取用户信息
     * @param string $username 用户名
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByUsername(string $username)
    {
        return User::query()->where('username', $username)->first();
    }

    /**
     * 根据手机获取用户信息
     * @param string $phone 手机
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getByPhone(int $phone)
    {
        return User::query()->where('mobile', $phone)->first();
    }

    /**
     * 用户id
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getUserRoleById($userId){

        return User::query()->where('id', $userId)->first();
    }

}
