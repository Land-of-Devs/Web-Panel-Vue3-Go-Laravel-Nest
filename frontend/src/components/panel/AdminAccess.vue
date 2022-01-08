<template>
  <div class="grid row">
    <div class="flex xs12 md6 offset--md3">
      <va-card>
        <va-card-title>Admin authentication required</va-card-title>
        <va-card-content>
          <h3>Please enter the two step verification code to access admin functions:</h3>
          <br/>
          <va-input v-model="code" placeholder="Verification code" />
        </va-card-content>
        <va-card-actions>
          <va-button :disabled="code.length == 6" color="primary" @click="proceedUpgrade()">OK</va-button>
        </va-card-actions>
      </va-card>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";

export default defineComponent({
  setup() {
    const code = ref("");

    async function proceedUpgrade() {

    }

    return {
      code,
      proceedUpgrade
    };
  }
});
</script>