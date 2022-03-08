import request from '@/utils/request'

export function getRoutes() {
  return request({
    url: '/backend/auth/routes',
    method: 'get'
  })
}

export function getRoles() {
  return request({
    url: '/backend/auth/roles',
    method: 'get'
  })
}

export function addRole(data) {
  return request({
    url: '/backend/auth/role',
    method: 'post',
    data
  })
}

export function updateRole(id, data) {
  return request({
    url: `/backend/auth/role/${id}`,
    method: 'put',
    data
  })
}

export function deleteRole(id) {
  return request({
    url: `/backend/auth/role/${id}`,
    method: 'delete'
  })
}
