import { store } from '../store';

export const getRole = () => {
    return store.getters['user/getRole'];
}

export const isLoggedIn = () => {
    return store.getters['user/getRole'] != 0;
}

export const hasRole = (role) => {
    return store.getters['user/getRole'] >= role;
}

export const hasAdminAccess = () => {
    return hasRole(3) && store.getters['adminaccess/getUntil'];
}

export const isCreator = (id) => {
    return store.getters['user/getID'] == id;
}