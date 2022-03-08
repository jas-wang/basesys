<?php
namespace App\Library;

use Monolog\Logger;

/**
 * 自定义日志类实现
 * Class BLogger
 * @package App\Library
 */
class Log
{
   // 所有的LOG都要求在这里注册
    const LOG_ERROR = 'error';
    const LOG_DEBUG = 'debug';
    const LOG_INFO = 'info';

    private static $loggers = [];

    // 获取一个实例
    public static function record($type = self::LOG_ERROR, string  $name, array  $data)
    {
        $log = new Logger('local');
        $path = "logs/$type/$name.log";
        $log->pushHandler(new RotatingFileHandler(storage_path($path)))
           ->$type($name, $data);

    }
}
