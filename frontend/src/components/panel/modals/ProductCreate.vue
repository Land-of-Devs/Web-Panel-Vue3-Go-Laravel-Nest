<template>
    <va-modal
        title="Product"
        :modelValue="opened"
        ok-text="Success"
        :dataM="dataM"
        hide-default-actions
        size="large"
    >
        <ProductForm
            v-on:exit="state.edit = false"
            v-on:close="$emit('close')"
            :product="dataM"
            :action="'create'"
        />
    </va-modal>
</template>

<script>
import ProductForm from "../forms/ProductForm.vue";
export default {
    components: {
        ProductForm
    },
    props: ["opened", "dataM"]
};
</script>