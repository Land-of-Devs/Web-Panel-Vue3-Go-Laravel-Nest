<template>
  <va-modal
      :modelValue="opened"
      title="Reportar"
      ok-text="Success"
      hide-default-actions
      size="large"
    >
      <template #default>
        <va-form ref="formRef">
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
        </va-form>
        
      </template>
      
      <template #footer>
        <va-button @click="$emit('close')" text-color="gray" color="#6b6b6b" flat>Cancelar</va-button>
        <va-button :loading="state.loading" 
          @click="formRef.validate() && 
                  reportP(dataM) && 
                  $vaToast.init({message: 'Report sent!', color: 'success' }) &&
                  $emit('close')" gradient>Reportar</va-button>
        
      </template>
    </va-modal>
</template>

<script>
import { reactive, ref } from '@vue/reactivity';
import { reportTicket } from '../../../../services/products';
import * as validator from "/src/utils/validator";

export default {
  props: ['opened', 'dataM'],
  setup() {

    const formRef = ref(null);
    const val = validator;
    const state = reactive({
      title: '',
      message: '',
      loading: false,
      suggestion: false
    })

    async function reportP(product) {
      state.loading = true;
      await reportTicket(parseInt(product.id), state.title, state.message);
      state.loading = false;
    }
    const rules = reactive({
      title: [val.rules.required, val.rules.minLength(4)],
      msg: [val.rules.required, val.rules.minLength(10)]
    })

    return {
      reportP,
      state,
      formRef,
      rules
    }
  }
}
</script>