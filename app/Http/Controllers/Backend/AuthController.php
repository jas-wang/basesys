<?php


namespace App\Http\Controllers\Backend;

use App\Lang\Auth\UserLang;
use App\Lang\CommonLang;
use App\Services\Auth\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Data\Source;

class AuthController extends BackendController
{

    public function routes(){

        return $this->success(Source::ROUTES);
    }
    public function roles(){

        return $this->success(Source::ROLES);
    }
    public function register()
    {
        return 1;

    }

    /**
     * 用户登录
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array
    {
        //获取用户名密码
        $username = $request->input('username');
        $password = $request->input('password');
        //检查用户名密码
        if (empty($username) || empty($password)) {
            return $this->fail(CommonLang::PARAM_ILLEGAL);
        }
        //验证数据是否存在
        $user = UserService::getInstance()->getByUsername($username);
        if (is_null($user)) {
            return $this->fail(UserLang::AUTH_INVALID_ACCOUNT);
        }
        //对密码进行验证
        if (sha1($password) != $user->getAuthPassword()) {
            return $this->fail(UserLang::AUTH_INVALID_PASSWORD);
        }
        //更新登录信息
        $user->last_login_ip = $request->getClientIp();
        $user->last_login_time = time();
        if (!$user->save()) {
            return $this->fail(CommonLang::UPDATED_FAIL);
        }
        $token = Auth::guard('backend')->login($user);
        return $this->success(
            [
                'token' => $token,
                'userInfo' => [
                    'username' => $username,
                    'nickname' => $user->nickname,
                    'avatarUrl' => $user->avatar,
                ],
            ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userinfo()
    {
        $userInfo = auth()->user();
        //$userInfo['roles'] = RoleService::getInstance()->getRoleByUserId($userInfo->getAuthIdentifier());
        $userInfo['roles'] = ['admin'];
        return $this->success( $userInfo);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->success();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
