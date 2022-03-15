import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'


/**
 * 路由
 */
/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path(.*)',
        component: () => import('@/views/redirect/index')
      }
    ]
  },
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/auth-redirect'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/error-page/404'),
    hidden: true
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true
  },
  {
    path: '/',
    component: Layout,
    redirect: '/index',
    children: [
      {
        path: 'index',
        component: () => import('@/views/dashboard/index'),
        name: 'index',
        meta: { title: '数据统计', icon: 'dashboard', affix: true }
      }
    ]
  },

  {
    path: '/profile',
    component: Layout,
    redirect: '/profile/index',
    hidden: true,
    children: [
      {
        path: 'index',
        component: () => import('@/views/profile/index'),
        name: 'profile',
        meta: { title: '个人信息', icon: 'user', noCache: true }
      }
    ]
  }
]

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = [

  {
    path: '/order',
    component: Layout,
    redirect: '',
    name: 'Order',
    meta: {
      title: '订单管理',
      icon: 'el-icon-s-help'
    },
    children: [
      {
        path: 'orderTodo',
        component: () => import('@/views/order/orderTodo'),
        name: 'orderTodo',
        meta: {  roles: ['editor'],title: '订单管理',icon: 'edit' }
      },
    ]
  },

  {
    path: '/goods',
    component: Layout,
    redirect: '',
    name: 'goods',
    meta: {
      title: '商品管理',
      icon: 'el-icon-s-help'
    },
    children: [
      {
        path: 'goodslist',
        component: () => import('@/views/order/orderTodo'),
        name: 'orderTodo1',
        meta: {  roles: ['editor'],title: '商品列表',icon: 'edit' }
      },
      {
        path: 'cate',
        component: () => import('@/views/goods/cate'),
        name: 'orderDone1',
        meta: {  roles: ['admin'],title: '分类管理',icon: 'edit' }
      }
    ]
  },

  {
    path: '/permission',
    component: Layout,
    redirect: '/permission/page',
    alwaysShow: true, // will always show the root menu
    name: 'Permission',
    meta: {
      title: '权限管理',
      icon: 'lock',
      roles: ['admin', 'editor'] // you can set roles in root nav
    },
    children: [
      {
        path: 'page',
        component: () => import('@/views/permission/role'),
        name: 'PagePermission',
        meta: {
          title: '角色列表',
          roles: ['user'] // or you can only set roles in sub nav
        }
      }
    ]
  },

  {
    path: '/basic',
    component: Layout,
    redirect: '/permission/page',
    alwaysShow: true, // will always show the root menu
    name: 'Permission',
    meta: {
      title: '基础数据',
      icon: 'lock',
      roles: ['admin', 'editor'] // you can set roles in root nav
    },
    children: [
      {
        path: 'user',
        component: () => import('@/views/permission/page'),
        name: 'user',
        meta: {
          title: '用户管理',
          roles: ['user'] // or you can only set roles in sub nav
        }
      },
      {
        path: 'config',
        component: () => import('@/views/permission/role'),
        name: 'config',
        meta: {
          title: '系统配置',
          roles: ['admin']
        }
      }
    ]
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
