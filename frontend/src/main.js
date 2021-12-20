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
