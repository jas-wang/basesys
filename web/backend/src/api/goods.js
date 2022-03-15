import request from '@/utils/request'
/*
 * 获取分类列表
 */
export function getCateList(query) {
  return request({
    url: '/backend/goods/getCateList',
    method: 'get',
    params: query
  })
}
/*
 * 获取分类列表
 */
export function getParentCateList(query) {
  return request({
    url: '/backend/goods/getParentCateList',
    method: 'get',
    params: query
  })
}
