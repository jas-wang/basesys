<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use App\Notifications\VerificationCode;
use Illuminate\Support\Facades\Notification;
use Leonis\Notifications\EasySms\Channels\EasySmsChannel;
use Overtrue\EasySms\PhoneNumber;
class BaseService
{


    protected static $instance = [];

    /**
     * 获取实列
     * @return static
     */
    public static function getInstance(): BaseService
    {
        if ((static::$instance[static::class] ?? null) instanceof static) {
            return static::$instance[static::class];
        }
        return static::$instance[static::class] = new static();
    }

    /**
     * 检查手机验证吗发送次数
     * @param string $key
     * @param int $time
     * @return bool
     */
    public function checkCodeSendTime(string $key,int $time=100): bool
    {
       if(Cache::has($key)){
           $count = Cache::increment($key);
           if($count>$time){
               return false;
           }
       }else{
           Cache::put($key, 1, Carbon::tomorrow()->diffInSeconds(now()));
       }
       return true;
    }

    /**
     * 设置手机短信验证码
     * @param string $phone
     * @param string $prefix
     * @return string
     * @throws Exception
     */
    public function setCode(string $phone,string $prefix = ''): string
    {
        // 随机生成6位验证码
        $code = random_int(100000, 999999);
        $code = strval($code);
        Cache::put($prefix.$phone, $code, 600);
        return $code;
    }

    /**
     * 发送验证码短信
     * @param  string  $mobile
     * @param  string  $code
     */
    public function sendCodeMsg(string $mobile, string $code)
    {
        // 发送短信
        Notification::route(
            EasySmsChannel::class,
            new PhoneNumber($mobile, 86)
        )->notify(new VerificationCode($code));
    }

    /**
     * 检查验证码
     * @param string $prefix
     * @param int $phone
     * @return bool
     */
    public function checkCode(int $phone,string $prefix = '',int $code){
        if ( $code == Cache::get($prefix.$phone)) {
            return true;
        }
        return false;
    }
}
