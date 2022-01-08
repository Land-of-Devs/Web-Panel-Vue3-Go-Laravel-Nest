import { createStore } from 'vuex';
import { getCookieJson } from '../utils/cookie';
import { adminAccessStore } from './adminaccess';
import { userStore } from './user';

export const store = createStore({
  modules: {
    user: userStore,
    adminaccess: adminAccessStore
  },
  plugins: [
    store => {
      try {
        if ('cookieStore' in window) {
          window.cookieStore.addEventListener('change', () => {
            store.dispatch('user/setUser', getCookieJson('userdata'));
            store.dispatch('adminaccess/setUntil', getCookieJson('adminaccess'));
          });
        }
      } catch (e) {
        console.error(e);
      }
    }
  ]
})
