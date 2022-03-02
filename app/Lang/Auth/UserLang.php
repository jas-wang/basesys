<?php

namespace App\Lang\Auth;

class UserLang
{

    const AUTH_INVALID_ACCOUNT = [700, '账号不存在'];
    const AUTH_CAPTCHA_UNSUPPORT = [701, ''];
    const AUTH_CAPTCHA_FREQUENCY = [702, '验证码未超时1分钟，不能发送'];
    const AUTH_CAPTCHA_UNMATCH = [703, '验证码错误'];
    const AUTH_NAME_REGISTERED = [704, '用户已注册'];
    const AUTH_MOBILE_REGISTERED = [705, '手机号已注册'];
    const AUTH_MOBILE_UNREGISTERED = [706, '手机号未注册'];
    const AUTH_INVALID_MOBILE = [707, '手机号格式不正确'];
    const AUTH_OPENID_UNACCESS = [708, ''];
    const AUTH_INVALID_PASSWORD = [709, '密码不对'];
}
