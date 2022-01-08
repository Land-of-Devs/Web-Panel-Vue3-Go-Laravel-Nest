<template>
    <va-modal
        title="Product"
        :modelValue="opened"
        ok-text="Success"
        :dataM="dataM"
        hide-default-actions
        size="large"
    >
        <template v-if="state.edit">
            <ProductForm
                v-on:exit="state.edit = false"
                v-on:close="$emit('close')"
                :product="dataM"
                :action="'update'"
            />
        </template>
        <template v-else>
            <ProductCard
                v-on:edit="state.edit = true"
                v-on:close="$emit('close')"
                :product="dataM.product.value"
                :canEdit="true"
            />
        </template>
    </va-modal>
</template>

<script>
import { reactive } from "vue";
import ProductForm from "../forms/ProductForm.vue";
import ProductCard from "/src/components/global/cards/ProductCard.vue";
export default {
    components: {
        ProductForm,
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