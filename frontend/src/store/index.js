import { createStore } from 'vuex';
import { userStore } from './user';

export const store = createStore({
  modules: {
    user: userStore
  }
})
