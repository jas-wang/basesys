<?php

namespace App\Services;

use App\Library\Log;
use GuzzleHttp\Client;
use Illuminate\Log\Logger;

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
