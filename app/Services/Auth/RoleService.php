<?php

namespace App\Services\Auth;

use App\Models\Auth\Role;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

/**
 * 角色服务中间件
 */
class RoleService extends BaseService
{


    /**
     * 获取用户角色
     * @param $userId
     * @return mixed
     */
    public function getRoleByUserId($userId){

        $fields = ['r.name'];
        return DB::table('role as r')->leftJoin('user_role as ur',function ($join){
            $join->on('ur.roleId','=','r.id');
        })->where('ur.userId', $userId)->select($fields)->get();
    }

}
