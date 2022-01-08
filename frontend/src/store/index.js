import { createStore } from 'vuex';
import { getCookieJson } from '../utils/cookie';
import { userStore } from './user';

export const store = createStore({
  modules: {
    user: userStore
  },
  plugins: [
    store => {
      try {
        if ('cookieStore' in window) {
          window.cookieStore.addEventListener('change', () => {
            store.commit('user/SETUSER', getCookieJson('userdata'));
          })
        }
      } catch (e) {
        console.error(e);
      }
    }
  ]
})
