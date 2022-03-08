<?php

namespace App\Models\Order;




use App\Models\BaseModel;

class Order extends BaseModel
{

    public $table = 'order';
    public const CREATED_AT = 'add_time';

    public const UPDATED_AT = 'update_time';
}
