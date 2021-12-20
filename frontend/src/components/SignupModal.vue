<template>
  <va-modal title="Sign up" :modelValue="opened" ok-text="Sign up" hide-default-actions>
    <va-form ref="formRef" autofocus>
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
        :rules="[
            value => (value && value.length > 0) || 'Field is required',
            value => /^[a-zA-Z0-9]+$/.test(value) || 'Invalid username'
        ]"
        label="username"
        class="mb-4"
        v-model="state.form.username"
        >
      </va-input>
      <va-input
        :rules="[value => (value && value.length > 0) || 'Field is required']" 
        label="password" 
        class="mb-4" 
        v-model="state.form.password" 
        type="password"
        @keyup.enter="formRef.validate() && signup()"
      ></va-input>
    </va-form>

    <template #footer>
      <va-button text-color="primary" class="mr-1" @click="goSignin()" flat>Cancel</va-button>
      <va-button text-color="white" class="ml-1" @click="formRef.validate() && signup()" gradient>Sign up</va-button>
    </template>
  </va-modal>
</template>

<script>
import { reactive, ref } from 'vue';
import useApi from '../composables/useApi';
import useEmitter from '../composables/useEmitter';
import SigninModalVue from './SigninModal.vue';
import { useStore } from 'vuex';

export default {
  props: ['opened'],
  setup() {

    const formRef = ref(null);
    const state = reactive({
      err: '',
      loading: false,
      form: {
        email: '',
        username: '',
        password: ''
      }
    })

    const emitter = useEmitter();
    const api = useApi();
    const store = useStore();

    function signup() {      
      state.loading = true;
      api.signup(state.form).then(({data}) => {
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

    function goSignin() {
      emitter.emit('modal/open', SigninModalVue);
    }
    
    return {
      signup,
      goSignin,
      state,
      formRef
    }
  }
}

</script>
