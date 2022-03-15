<?php


namespace App\Http\Controllers\Backend;
use App\Services\Goods\GoodsService;
use Illuminate\Http\Request;

class GoodsController extends BackendController
{
    protected $only = ['getPageList'];

    /**
     * 获取分类列表
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getCateList(Request $request)
    {
        $page = $request->input('page', $this->defaultPage);
        $pageSize = $request->input('limit', $this->defaultPageSize);
        $list = GoodsService::getInstance()->getCateList($page, $pageSize);
        return $this->successPaginate($list);

    }

    /**
     * 获取所有上级
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getParentCateList(Request $request)
    {
        $list = GoodsService::getInstance()->getParentCateList();
        return $this->success($list);

    }

    /**
     * 获取上级
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getCate(Request $request)
    {
        $id = $request->input('id', 0);
        $list = GoodsService::getInstance()->getCate($id);
        return $this->success($list);

    }
}
