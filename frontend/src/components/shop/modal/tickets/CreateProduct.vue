<template>
  <va-modal
      :modelValue="opened"
      title="Sugerir"
      ok-text="Success"
      hide-default-actions
      size="large"
    >

      
      <template #default>
        <va-form ref="formRef">
          <va-select class="mb-4" label="option" v-model="state.option" :options="['Create Product', 'Update User']" />
          <template v-if="state.option == 'Create Product'">
            <va-input
              class="mb-4"
              label="Title"
              :rules="rules.title"
              v-model="state.title"
            />
            <va-input
              v-model="state.message"  
              class="mb-4"
              type="textarea"
              label="Message"
              :min-rows="1"
              :max-rows="5"
              :rules="rules.msg"
            />
          </template>
          <template v-else>
            <va-input
              v-model="state.email"  
              class="mb-4"
              label="New Email"
              :rules="rules.email"
            />
            <va-input
              v-model="state.name"  
              class="mb-4"
              label="New Name"
              :rules="rules.name"
            />
            <va-input
              v-model="state.password"
              class="mb-4"
              type="password"
              label="New Password"
              :rules="rules.password"
            />

          </template>
          
        </va-form>
      </template>
      
      <template #footer>
        <va-button @click="$emit('close')" text-color="gray" color="#6b6b6b" flat>Cancelar</va-button>
        <va-button :loading="state.loading" 
          @click="formRef.validate() && 
                  submit(dataM) && 
                  $vaToast.init({message: 'Report sent!', color: 'success' }) &&
                  $emit('close')" gradient>Sugerir</va-button>
        
      </template>
    </va-modal>
</template>

<script>
import { reactive, ref } from '@vue/reactivity';
import { requestTicket, userTicket } from '../../../../services/products';
import * as validator from "/src/utils/validator";

export default {
  props: ['opened', 'dataM'],
  setup() {

    const formRef = ref(null);
    const state = reactive({
      title: '',
      message: '',
      password: '',
      email: '',
      name: '',
      option: 'Create Product',
      loading: false
    })

    const val = validator;

    async function submit() {
      state.loading = true;
      if (state.option == 'Create Product') {
        await requestTicket(state.title, state.message);
      } else {
        await userTicket(state.email, state.name, state.password);
      }
      
      state.loading = false;
    }

    return {
      submit,
      state,
      formRef,
      rules: {
        title: [val.rules.required, val.rules.minLength(4)],
        msg: [val.rules.required, val.rules.minLength(10)],
        name: [val.rules.required, val.rules.username],
        email: [val.rules.required, val.rules.email],
        password: [val.rules.required, val.rules.password]

      }
    }
  }
}
</script>


