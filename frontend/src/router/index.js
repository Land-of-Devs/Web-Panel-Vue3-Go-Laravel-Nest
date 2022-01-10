import { createRouter, createWebHistory } from 'vue-router';
import { store } from '../store';
import Home from '../views/Home.vue';
import Shop from '../views/Shop.vue';

const privilegeGuard = (next, role) => {
  if (store.getters['user/getRole'] >= role) {
    next();
  } else {
    next('/');
  }
};

const adminAccessGuard = (to, from, next) => {
  if (!store.getters['adminaccess/getUntil']) {
    next('/panel/admin-access?to=' + encodeURIComponent(to.fullPath));
  } else {
    next();
  }
};

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/shop',
    name: 'Shop',
    component: Shop
  },
  {
    path: '/panel',
    name: 'Panel',
    beforeEnter: (to, from, next) => privilegeGuard(next, 2),
    component: () => import('../views/Panel.vue'),
    children: [
      {
        path: '',
        name: 'Panel.Dashboard',
        component: () => import('../components/panel/Dashboard.vue')
      },
      {
        path: 'products',
        name: 'Panel.Products',
        component: () => import('../components/panel/Products.vue')
      },
      {
        path: 'tickets',
        name: 'Panel.Tickets',
        component: () => import('../components/panel/Tickets.vue')
      },
      {
        path: 'users',
        name: 'Panel.Users',
        beforeEnter: [
          (to, from, next) => privilegeGuard(next, 3),
          adminAccessGuard
        ],
        component: () => import('../components/panel/Users.vue')
      },
      {
        path: 'admin-access',
        name: 'Panel.AdminAccess',
        beforeEnter: (to, from, next) => privilegeGuard(next, 3),
        component: () => import('../components/panel/AdminAccess.vue')
      }
    ]
  }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router
