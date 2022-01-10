import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { store } from './store';
import { VuesticPlugin } from 'vuestic-ui';
import 'vuestic-ui/dist/vuestic-ui.css';
import './styleConfig';
import { config } from './styleConfig';
import axios from 'axios';
import VueAxios from 'vue-axios';

// axios config
axios.defaults.baseURL = window.location.origin + "/api/";

createApp(App)
.use(store)
.use(router)
.use(VueAxios, axios)
.use(VuesticPlugin, config)
.mount('#app');


const oldVals = {
  role: null,
  adminaccess: null
};

/* Watch changes in the store to re-check current route guards */
store.subscribe((mutation) => {
  switch (mutation.type) {
    case 'user/DELUSER':
      mutation.payload = {role: 0};
      // falls through
    case 'user/SETUSER':
      if (oldVals.role !== null && oldVals.role > mutation.payload.role) {
        router.go(); /* reload to reapply guards */
      }

      oldVals.role = mutation.payload.role;
      break;

    case 'adminaccess/SETUNTIL':
      if (oldVals.adminaccess !== null && oldVals.adminaccess > mutation.payload) {
        router.go(); /* reload to reapply guards */
      }

      oldVals.adminaccess = mutation.payload;
      break;
  }
});
