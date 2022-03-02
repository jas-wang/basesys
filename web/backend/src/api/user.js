import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/backend/auth/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/backend/auth/userinfo',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/backend/user/logout',
    method: 'post'
  })
}
