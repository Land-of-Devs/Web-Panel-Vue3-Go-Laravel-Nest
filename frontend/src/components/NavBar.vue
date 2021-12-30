<template>
  <va-navbar class="shadow" v-if="!isInPanel()">
    <template #left>
      <va-navbar-item>Web-Panel</va-navbar-item>
    </template>
    <template #right>
      <va-navbar-item>
        <va-button text-color="primary" flat>Home</va-button>
      </va-navbar-item>
      <va-navbar-item>
        <va-button text-color="primary" flat>Shop</va-button>
      </va-navbar-item>
      <va-navbar-item  v-if="role == 0" >
        <va-button @click="signIn" text-color="primary" flat>Login</va-button>
      </va-navbar-item>
      <va-navbar-item v-if="role >= 2">
        <router-link to="Panel">
          <va-button text-color="primary" flat>Panel</va-button>
        </router-link>
      </va-navbar-item>
    </template>
  </va-navbar>  
</template>
<script>

import { computed } from 'vue';
import { useStore } from 'vuex';
import useEmitter from '../composables/useEmitter';
import { useRoute } from 'vue-router';
import SignInModalVue from './SignInModal.vue';

export default {
  setup() {
    const emitter = useEmitter();
    const store = useStore();

    const role = computed(() => store.getters['user/getRole']);
    const route = useRoute();

    function isInPanel() {
      if (route.name) {
        return route.name.startsWith('Panel');
      }

    }

    function signIn() {
      emitter.emit('modal/open', SignInModalVue);
    }

    return {
      signIn,
      store,
      role,
      isInPanel
    }
  }
}
</script>

