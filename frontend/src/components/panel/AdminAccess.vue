<template>
  <div class="grid row">
    <div class="flex xs12 md6 offset--md3">
      <va-card>
        <va-card-title>Admin authentication required</va-card-title>
        <va-card-content>
          <h3 class="mb-3">Please enter the two step verification code to access admin functions:</h3>
          <va-form ref="codeForm">
            <va-input 
                :rules="codeRules"
                v-model="state.code"
                :maxlength="6"
                placeholder="Verification code"
                @change="void(errorMsg = '')"
                @keyup.enter="codeForm.validate() && proceedUpgrade()" />
          </va-form>
          <b style="color: red;">{{errorMsg}}</b>
        </va-card-content>
        <va-card-actions>
          <va-button color="primary" @click="codeForm.validate() && proceedUpgrade()">OK</va-button>
        </va-card-actions>
      </va-card>
    </div>
  </div>
</template>

<script>
import { defineComponent, reactive, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { adminUpgrade } from "../../services/auth";
import { hasAdminAccess } from "../../utils/store";
import * as validator from "/src/utils/validator";

export default defineComponent({
  setup() {
    const state = reactive({code: null});
    const codeForm = ref(null);
    const errorMsg = ref("");

    const $router = useRouter();
    const $route = useRoute();

    function redirectToTarget() {
      const url = $route.query.to;
      $router.replace(url);
    }

    async function proceedUpgrade() {
      try {
        errorMsg.value = ""; 
        await adminUpgrade(state);
        redirectToTarget();
      } catch (e) {
        if (e.response) {
          errorMsg.value = e.response && e.response.status === 401 ? 'The code is not valid.' : e.message;
        }
      }
    }

    if (hasAdminAccess()) {
      redirectToTarget();
    }

    return {
      state,
      codeForm,
      errorMsg,
      codeRules: [validator.rules.twostepcode],
      proceedUpgrade
    };
  }
});
</script>