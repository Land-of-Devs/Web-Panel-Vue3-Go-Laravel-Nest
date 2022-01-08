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
        @keyup.enter="formRef.validate() && signIn()"
      ></va-input>
      <div class="no-account">¿You don't have an account? <span @click="goSignUp()">Sign up</span></div>

    </va-form>

    <template #footer>
      <va-button text-color="primary" class="mr-1" @click="$emit('close')" flat>Cancel</va-button>
      <va-button :loading="state.loading" text-color="white" class="ml-1" @click="formRef.validate() && signIn()" gradient>Sign in</va-button>
    </template>
  </va-modal>
</template>

<script>
import { reactive, ref } from 'vue';
import * as auth from '../../services/auth';
import useEmitter from '../../composables/useEmitter';
//import { useStore } from 'vuex';
import SignUpModalVue from './SignUpModal.vue';

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

    const emitter = useEmitter();
    //const store = useStore();

    function signIn() {      
      state.loading = true;
      auth.signIn(state.form)
      .then(() => {
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
        } else {
          state.err = 'Unknown error';
        }
      }).finally(() => state.loading = false);
    }

    function goSignUp() {
      emitter.emit('modal/open', {view: SignUpModalVue});
    }
    
    return {
      signIn,
      goSignUp,
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
