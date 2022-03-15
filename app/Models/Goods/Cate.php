<?php

namespace App\Models\Goods;




use App\Models\BaseModel;

class Cate extends BaseModel
{

    public $table = 'category';
    public const CREATED_AT = 'add_time';

    public const UPDATED_AT = 'update_time';
}
