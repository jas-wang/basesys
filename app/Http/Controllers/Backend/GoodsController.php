<?php


namespace App\Http\Controllers\Backend;
use App\Services\Goods\GoodsService;
use Illuminate\Http\Request;

class GoodsController extends BackendController
{
    protected $only = ['getPageList'];

    /**
     * 获取订单列表
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getCateList(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('limit', 10);
        $list = GoodsService::getInstance()->getCateList($page, $pageSize);
        return $this->successPaginate($list);

    }

}
