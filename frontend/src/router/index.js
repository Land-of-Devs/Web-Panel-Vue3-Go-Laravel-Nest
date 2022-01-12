import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import { adminAccessGuard, privilegeGuard } from '../guards/access';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/verify/:token',
        component: () => import('../components/Verify.vue')
    },
    {
        path: '/shop',
        name: 'Shop',
        beforeEnter: (to, from, next) => privilegeGuard(next, 1),
        component: () => import('../views/Shop.vue')
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
    },
    {
        path: '/:pathMatch(.*)',
        redirect: '/'
    },
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})


export default router