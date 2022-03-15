<?php

namespace App\Models\Goods;




use App\Models\BaseModel;

class Goods extends BaseModel
{

    public $table = 'goods';
    public const CREATED_AT = 'add_time';

    public const UPDATED_AT = 'update_time';
}
