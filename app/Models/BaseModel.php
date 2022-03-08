<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{


    /**
     * 实例化
     * @return static
     */
    public static function new()
    {
        return new static();
    }
}
