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
    url: '/backend/auth/logout',
    method: 'post'
  })
}

export function modifyPass(data) {
  return request({
    url: '/backend/auth/reset',
    method: 'post',
    data
  })
}
export function getCode(data) {
  return request({
    url: '/backend/auth/getCode',
    method: 'post',
    data
  })
}
