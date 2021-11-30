<template>
  <va-navbar class="shadow">
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
      <va-navbar-item>
        <va-button @click="login" text-color="primary" flat>Login</va-button>
      </va-navbar-item>
    </template>
  </va-navbar>  
</template>

<script>
import useEmitter from '../composables/useEmitter';
import LoginModal from '../components/LoginModal.vue';


export default {
  setup() {
    const emitter = useEmitter();

    function login() {
      emitter.emit('modal/open', LoginModal);
    }

    return {
      login
    }
  }
}
</script>

