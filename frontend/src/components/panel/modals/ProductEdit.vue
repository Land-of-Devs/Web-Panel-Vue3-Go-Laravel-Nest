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
                :canEdit="creator"
            />
        </template>
    </va-modal>
</template>

<script>
import { reactive, computed, ref } from "vue";
import ProductForm from "../forms/ProductForm.vue";
import ProductCard from "/src/components/global/cards/ProductCard.vue";
import { hasAdminAccess, isCreator } from "/src/utils/store";

export default {
    components: {
        ProductForm,
        ProductCard,
    },
    props: ["opened", "dataM"],
    setup(props) {
        const state = reactive({
            edit: false,
        });
        const user = ref(props.dataM.product.value["user"]);
        const creator = computed(
            () =>
                (user.value && isCreator(user.value["id"])) ||
                hasAdminAccess()
        );
        return {
            state,
            creator,
        };
    },
};
</script>