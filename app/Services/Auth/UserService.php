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


    public function getUserRoleById($userId){

        return User::query()->where('id', $userId)->first();
    }

}
