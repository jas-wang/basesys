<?php


namespace App\Http\Controllers\Backend;
use App\Lang\CommonLang;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

class OrderController extends BackendController
{
    protected $only = ['getPageList'];
    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', 10);

        $list = OrderService::getInstance()->getOrderList($page, $pageSize);
        return $this->successPaginate($list);

    }

    /**
     * 获取订单列表
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getPageList(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('limit', 10);
        $form['order_sn'] = $request->input('orderNo','');
        $form['order_status'] = $request->input('orderStatus','');
        $list = OrderService::getInstance()->getOrderList($page, $pageSize,$form);
        return $this->successPaginate($list);

    }

    /**
     * 更新订单状态
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updateOrderStatus(Request $request)
    {
        $status = $request->input('status');
        $id = $request->input('id', 1);
        $res= OrderService::getInstance()->updateStatus($id,$status);
        return $res? $this->success($res):$this->fail(CommonLang::UPDATED_FAIL);

    }
    /**
     * 查看物流
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getShipInfo(Request $request)
    {
        $ship_sn = $request->input('ship_sn');
        $res = OrderService::getInstance()->getShipInfo($ship_sn);
        return $this->outPut(CommonLang::SUCCESS,$res);

    }
}
