<?php

namespace App\Services\Goods;


use App\Models\Goods\Cate;
use App\Services\BaseService;

/**
 * 商品
 */
class GoodsService extends BaseService
{


    /**
     * 获取定胆列表
     * @param int $page
     * @param int $limit
     * @param array $form
     * @param $order
     * @param $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCateList(int $page, int $limit,$columns = ['*'])
    {
        $query = Cate::query();
        return $query->paginate($limit, $columns, 'page', $page);
    }

}


