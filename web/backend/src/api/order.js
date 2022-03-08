import request from '@/utils/request'
/*
 * 获取订单列表
 */
export function getPageList(query) {
  return request({
    url: '/backend/order/getPageList',
    method: 'post',
    params: query
  })
}
/*
 * 更新订单确认状态
 */
export function updateOrderStatus(status) {
  return request({
    url: '/backend/order/updateOrderStatus',
    method: 'post',
    params: status
  })
}

/*
 * 查看物流
 */
export function getShipInfo(query) {
  return request({
    url: '/backend/order/getShipInfo',
    method: 'post',
    params: query
  })
}
