<template>
  <va-modal title="Sign in" :modelValue="opened" ok-text="Login" hide-default-actions>
    <va-form ref="formRef">
      <div class="form-err">{{ state.err }}</div>
      <va-input 
        :rules="[
            value => (value && value.length > 0) || 'Field is required',
            value => /\S+@\S+\.\S+/.test(value) || 'Must be an email'
            ]" 
        label="email" 
        class="mb-4" 
        type="email"
        v-model="state.form.email">
      </va-input>
      <va-input
        :rules="[value => (value && value.length > 0) || 'Field is required']" 
        label="password" 
        class="mb-4" 
        v-model="state.form.password" 
        type="password"
        @keyup.enter="formRef.validate() && signin()"
      ></va-input>
      <div class="no-account">¿You don't have an account? <span @click="goSignup()">Sign up</span></div>

    </va-form>

    <template #footer>
      <va-button text-color="primary" class="mr-1" @click="$emit('close')" flat>Cancel</va-button>
      <va-button :loading="state.loading" text-color="white" class="ml-1" @click="formRef.validate() && signin()" gradient>Sign in</va-button>
    </template>
  </va-modal>
</template>

<script>
import { reactive, ref } from 'vue';
import useApi from '../composables/useApi';
import useEmitter from '../composables/useEmitter';
import SignupModal from '../components/SignupModal.vue';
import { useStore } from 'vuex';

export default {
  props: ['opened'],
  setup() {

    const formRef = ref(null);
    const state = reactive({
      loading: false,
      err: '',
      form: {
        email: '',
        password: ''
      }
    });

    const api = useApi();
    const emitter = useEmitter();
    const store = useStore();

    function signin() {      
      state.loading = true;
      api.signin(state.form)
      .then(({ data }) => {
        store.dispatch('user/setUser', data);
        emitter.emit('modal/close');
      })
      .catch(e => {
        if (e.response) {
          let { message } = e.response.data;
          if (Array.isArray(message) && message.length) {
            state.err = message[0];
          } else {
            state.err = message;
          }
        }
      }).finally(() => state.loading = false);
    }

    function goSignup() {
      emitter.emit('modal/open', SignupModal);
    }
    
    return {
      signin,
      goSignup,
      state,
      formRef
    }
  }
}

</script>

<style lang="scss" scoped>
.no-account {
  font-size: 10pt;
  text-align: center;
  span {
    color: var(--va-primary);
    cursor: pointer;
  }
}

</style>
