import request from '@/utils/request'

export function searchUser(name) {
  return request({
    url: '/backend/auth/searchuser',
    method: 'get',
    params: { name }
  })
}

export function transactionList(query) {
  return request({
    url: '/backend/order/list',
    method: 'get',
    params: query
  })
}
