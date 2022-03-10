<?php

namespace App\Lang\Auth;

class UserLang
{

    const AUTH_INVALID_ACCOUNT = [700, '账号不存在'];
    const AUTH_CAPTCHA_TIME_OUT = [701, '验证码当天发送不能超过20次'];
    const AUTH_CAPTCHA_FREQUENCY = [702, '验证码未超时1分钟，不能发送'];
    const AUTH_CAPTCHA_UNMATCH = [703, '验证码错误'];
    const AUTH_NAME_REGISTERED = [704, '用户已注册'];
    const AUTH_MOBILE_REGISTERED = [705, '手机号已注册'];
    const AUTH_MOBILE_UNREGISTERED = [706, '手机号未注册'];
    const AUTH_INVALID_PHONE = [707, '手机号格式不正确'];
    const AUTH_RESET_PASSWD = [708, '修改密码与原密码相同'];
    const AUTH_INVALID_PASSWORD = [709, '密码错误'];
    const LOGIN_EXPIRED = [800, '登录失效，请重新登录'];
}
