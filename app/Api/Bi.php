<?php
/**
 * 外部接口类
 */

namespace App\Api;

class Bi
{
    protected static $instance = [];

    /**
     * 获取实列
     * @return static
     */
    public static function getInstance(): Bi
    {
        if ((static::$instance[static::class] ?? null) instanceof static) {
            return static::$instance[static::class];
        }
        return static::$instance[static::class] = new static();
    }


    /**
     * 待实现
     * @return void
     */
    protected
    function request()
    {

    }
}
