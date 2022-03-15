<?php

namespace App\Services\Order;


use App\Api\ShipBi;
use App\Models\Order\Goods;
use App\Models\Order\Order;
use App\Services\BaseService;

/**
 * 角色服务中间件
 */
class OrderService extends BaseService
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
    public function getOrderList(int $page, int $limit, array $form,$columns = ['*'])
    {
        $query = Order::query();
        if (!empty($sort) && !empty($order)) {
            $query = $query->orderBy($sort, $order);
        }
        //订单编号
        if(isset($form['order_sn']) && !empty($form['order_sn'])){
            $query->where('order_sn','like',"%{$form['order_sn']}%");
        }
        //订单编号
        if(isset($form['order_status']) && !empty($form['order_status'])){
            $query->where('order_status',$form['order_status']);
        }
        return $query->paginate($limit, $columns, 'page', $page);
    }

    /**
     * 更新订单状态
     * @param $id
     * @param $status
     */
    public function updateStatus($id,$status){
        $order = Goods::query()->where('id',$id)->first();
        $order->order_status = $status;
        return $order->save();
    }

    /**
     * 查询物流信息
     * @param $shipSn
     */
    public function getShipInfo($shipSn): array
    {
        return ShipBi::getInstance()->expressTracking($shipSn);
    }
}


