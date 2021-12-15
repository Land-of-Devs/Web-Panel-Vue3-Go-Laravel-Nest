import { createRouter, createWebHistory } from 'vue-router';
import {store} from '../store';
import Home from '../views/Home.vue';

const priviledgeGuard = (next, role) => {
  if (store.getters['user/getRole'] >= role) {
    next();
  } else {
    next('/');
  }
}

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/panel',
    beforeEnter: (to, from, next) => priviledgeGuard(next, 2),
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
        path: 'Users',
        name: 'Panel.Users',
        beforeEnter: (to, from, next) => priviledgeGuard(next, 3),
        component: () => import('../components/panel/Users.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
