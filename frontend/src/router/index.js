import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Shop from '../views/Shop.vue';
import {verify} from '../services/auth';
import { adminAccessGuard, privilegeGuard } from '../guards/access';

const verifyUser = async (next, token) => {
    console.log(token)
    let user = false;
    if (user) {
        let user = await verify();
        if(user){
            console.log(user);
            next('/shop')
        }
    }
    next('/');
}

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/verify/:token',
        beforeEnter: (to, from, next) => verifyUser(next, to.params.token)
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
