<template>
  <va-navbar class="shadow" v-if="!isInPanel()">
    <template #left>
      <va-navbar-item>Web-Panel</va-navbar-item>
    </template>
    <template #right>
      <va-navbar-item>
        <router-link :to="{name: 'Home'}">
          <va-button text-color="primary" icon="home" flat></va-button>
        </router-link>
      </va-navbar-item>
      <va-navbar-item>
        <router-link :to="{name: 'Shop'}" v-if="role >= 1">
          <va-button text-color="primary" icon="shopping_cart" flat></va-button>
        </router-link>
      </va-navbar-item>
      <va-navbar-item  v-if="role == 0" >
        <va-button @click="signIn" text-color="primary" flat>Login</va-button>
      </va-navbar-item>
      <va-navbar-item v-if="role >= 2">
        <router-link :to="{name: 'Panel.Dashboard'}">
          <va-button text-color="primary" icon="admin_panel_settings" flat></va-button>
        </router-link>
      </va-navbar-item>
      <va-navbar-item v-if="role >= 1">
        <va-button @click="newTicket()" icon="confirmation_number" text-color="primary" flat>
        </va-button>
      </va-navbar-item>
      <va-navbar-item v-if="role != 0">
        <va-button @click="logOut()" text-color="primary" flat>
          ⠀⠀
          <va-icon name="meeting_room"></va-icon>
          ⠀⠀
        </va-button>
      </va-navbar-item>
    </template>
  </va-navbar>  
</template>
<script>

import { computed } from 'vue';
import { useStore } from 'vuex';
import useEmitter from '../composables/useEmitter';
import { useRoute } from 'vue-router';
import SignInModalVue from './login/SignInModal.vue';
import { signOut } from '../services/auth';
import CreateProductVue from './shop/modal/tickets/CreateProduct.vue';

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

    function newTicket() {
      emitter.emit('modal/open', {view: CreateProductVue})
    }

    function signIn() {
      emitter.emit('modal/open', {view: SignInModalVue});
    }

    function logOut() {
      signOut();
    }

    return {
      signIn,
      store,
      role,
      isInPanel,
      logOut,
      newTicket
    }
  }
}
</script>

