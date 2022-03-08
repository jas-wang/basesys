import request from '@/utils/request'
/*
 * 获取统计信息
 */
export function getPageCount() {
  return request({
    url: '/backend/main/count',
    method: 'get',
    params: null
  })
}
