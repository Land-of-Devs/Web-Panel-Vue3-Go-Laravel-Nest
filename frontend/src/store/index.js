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
      const reloadChanges = () => {
        store.dispatch('user/setUser', getCookieJson('userdata'));
        store.dispatch('adminaccess/setUntil', getCookieJson('adminaccess'));
      };

      try {
        if ('cookieStore' in window) {
          window.cookieStore.addEventListener('change', reloadChanges);
        } else {
          var checkCookie = function () {
            var lastCookie = document.cookie;
            return function () {
              var currentCookie = document.cookie;

              if (currentCookie != lastCookie) {
                lastCookie = currentCookie;
                reloadChanges();
              }
            };
          }();

          window.setInterval(checkCookie, 250);
        }
      } catch (e) {
        console.error(e);
      }
    }
  ]
})
