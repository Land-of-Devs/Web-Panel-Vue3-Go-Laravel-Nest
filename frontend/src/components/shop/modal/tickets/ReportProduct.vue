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
            v-model="state.title"
            label="Title"
            :rules="[
              value => (value && value.length > 0) || 'Field is required',
              value => (value && value.length >= 4) || 'Title must be at least 4 characters'
            ]"
          />
          <va-input
            class="mb-4"
            v-model="state.message"
            type="textarea"
            label="Message"
            :min-rows="1"
            :max-rows="5"
            :rules="[
              value => (value && value.length > 0) || 'Field is required',
              value => (value && value.length >= 10) || 'Message must be at least 10 characters'
            ]"
          />
        </va-form>
      </template>
      
      <template #footer>
        <va-button @click="$emit('close')" text-color="gray" color="#6b6b6b" flat>Cancelar</va-button>
        <va-button :loading="state.loading" 
          @click="formRef.validate() && 
                  reportP(dataM) && 
                  $vaToast.init({message: 'Report sent!', color: 'success' }) &&
                  $emit('close')
                  
                  " gradient>Reportar</va-button>
        
      </template>
    </va-modal>
</template>

<script>
import { reactive, ref } from '@vue/reactivity';
import { reportTicket } from '../../../../services/products';
export default {
  props: ['opened', 'dataM'],
  
  setup() {

    const formRef = ref(null);
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

    return {
      reportP,
      state,
      formRef
    }
  }
}
</script>