import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import { VuesticPlugin } from 'vuestic-ui';
import 'vuestic-ui/dist/vuestic-ui.css';
import './styleConfig';
import { config } from './styleConfig';

const app = createApp(App);

app
.use(store)
.use(router)
.use(VuesticPlugin, config)
.mount('#app');


