<?php

namespace App\Services;

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

}
