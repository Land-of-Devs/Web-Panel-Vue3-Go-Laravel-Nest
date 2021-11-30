<template>
  <va-modal title="Login" :modelValue="opened" ok-text="Login" hide-default-actions>
    <va-form tag="form" ref="formRef" autofocus>
      <va-input 
        :rules="[value => (value && value.length > 0) || 'Field is required']" 
        label="username" 
        class="mb-4" 
        v-model="form.username">
      </va-input>
      <va-input
        :rules="[value => (value && value.length > 0) || 'Field is required']" 
        label="password" 
        class="mb-4" 
        v-model="form.password" 
        type="password"
      ></va-input>
    </va-form>

    <template #footer>
      <va-button text-color="primary" class="mr-1" @click="$emit('close')" flat>Cancel</va-button>
      <va-button text-color="white" class="ml-1" @click="formRef.validate() && login()" gradient>Login</va-button>
    </template>
  </va-modal>
</template>

<script>
import { ref, shallowReactive } from 'vue';
export default {
  props: ['opened'],
  setup() {

    const form = shallowReactive({
      username: '',
      password: ''
    });

    const formRef = ref(null);

    function login() {
      console.log('login')
    }
    
    return {
      form,
      formRef,
      login
    }
  }
}

</script>
