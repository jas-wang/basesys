import request from '@/utils/request'

export function getCount(token) {
  return request({
    url: '/backend/schedule/index',
    method: 'get',
    params: { token }
  })
}
