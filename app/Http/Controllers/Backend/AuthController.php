<?php


namespace App\Http\Controllers\Backend;

use App\Exceptions\BusinessException;
use App\Lang\Auth\UserLang;
use App\Lang\CommonLang;
use App\Services\Auth\UserService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Data\Source;

class AuthController extends BackendController
{

    protected $auth = ['getCode'];


    public function routes()
    {

        return $this->success(Source::ROUTES);
    }

    /**
     * 获取用户角色
     * @return array|mixed
     */
    public function roles()
    {

        return $this->success(Source::ROLES);
    }

    /**
     * 获取手机验证码
     * @param Request $request
     * @return void
     */
    public function getCode(Request $request){
        $phone = $request->input('phone');
        //验证手机号是否正确
        if (!preg_match('/^1[0-9]{10}$/',$phone)) {
            return $this->fail(UserLang::AUTH_INVALID_PHONE);
        }
        //限制一分钟发一次
        $lock = Cache::add('modify_pass_code_lock_'.$phone,1,60);
        if (!$lock) {
            return $this->fail(UserLang::AUTH_CAPTCHA_FREQUENCY);
        }
        //限制每天只能发50次
        $resSendTime = UserService::getInstance()->checkCodeSendTime('modify_pass_code_count_'.$phone,20);
        if(!$resSendTime){
         return $this->fail(UserLang::AUTH_CAPTCHA_TIME_OUT);
        }
        //设置短信发送内容
        $code = UserService::getInstance()->setCode($phone,'modify_pass_code_');
        //发送短信
        UserService::getInstance()->sendCodeMsg($phone, $code);
        return $this->success();
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
        $token = auth()->login($user);
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
        return $this->success($userInfo);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (!auth()->check()) {
            return $this->success();
        }
        auth()->logout();
        return $this->success();

    }

    /**
     * 密码重置
     * @param Request $request
     * @return JsonResponse
     * @throws BusinessException
     */
    public function reset(Request $request)
    {
        $password = $request->input('password');
        $code = $request->input('code');
        $phone = $request->input('phone');
        if (empty($password) || empty($phone) || empty($code)) {
            return $this->fail(CommonLang::PARAM_ILLEGAL);
        }
       //检查验证码是否正确
        $check = UserService::getInstance()->checkCode($phone,'modify_pass_code_', $code);
        if (!$check) {
            return $this->fail(UserLang::AUTH_CAPTCHA_UNMATCH);
        }
        // 获取手机号是否正确
        $user = UserService::getInstance()->getByPhone($phone);
        if (is_null($user)) {
            return $this->fail(UserLang::AUTH_MOBILE_UNREGISTERED);
        }
        //判断密码是否原密码
        if(sha1($password) === $user->getAuthPassword()){
            return $this->fail(UserLang::AUTH_RESET_PASSWD);
        }
        $user->password = sha1($password);
        $ret = $user->save();
        if($ret){
            Cache::forget('modify_pass_code_'.$phone);
        }
        return $this->outPut($ret ? CommonLang::SUCCESS : CommonLang::FAIL);
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
