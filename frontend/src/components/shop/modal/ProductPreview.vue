<template>
    <va-modal
        title="Product"
        :modelValue="opened"
        ok-text="Success"
        :dataM="dataM"
        hide-default-actions
        size="large"
    >
      <ProductCard
          v-on:edit="state.edit = true"
          v-on:close="$emit('close')"
          :product="dataM.product"
          :canEdit="false"
      />
    </va-modal>
</template>

<script>
import { reactive } from "vue";
import ProductCard from '/src/components/panel/cards/ProductCard.vue';

export default {
    components: {
      ProductCard,
    },
    props: ["opened", "dataM"],
    setup() {
      const state = reactive({
          edit: false,
      });

      return {
          state,
      };
    },
};
</script>