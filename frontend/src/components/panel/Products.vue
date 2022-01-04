<template>
    <div class="flex">
        <va-tabs v-model="productType">
            <template #tabs>
                <va-tab name="all-products"> All Products </va-tab>
                <va-tab name="my-products"> My Products </va-tab>
            </template>
        </va-tabs>
    </div>
    <div class="datable">
        <va-data-table
            :items="list"
            :columns="columns"
            :current-page="page"
            :selectable="true"
            v-model="selectedItems"
            :clickable="true"
            :loading="!list.length"
            :striped="true"
        >
            <template #header(user)>Creator</template>
            <template #header(id)>Actions</template>
            <template #cell(price)="{ source: price }">{{ price }}€</template>

            <template #cell(user)="{ source: user }">
                <va-button
                    v-if="user.username"
                    color="#ffac0a"
                    gradient
                    @click="creatorPrev(user)"
                    >{{ user.username }}</va-button
                >
            </template>
            <template #cell(id)="{ source: id }">
                <va-button color="primary" gradient @click="productPrev(id)"
                    ><va-icon name="preview"
                /></va-button>
                <va-button color="danger" gradient
                    ><va-icon name="delete"
                /></va-button>
            </template>
        </va-data-table>
        <va-pagination v-model="page" input :pages="totalPages" />
    </div>
</template>

<script>
import { useProducts } from "/src/composables/useProducts";
import { defineComponent, ref, reactive } from "vue";
import useEmitter from "/src/composables/useEmitter";
import UserPreviewVue from "./modals/UserPreview.vue";
import ProductEditVue from "./modals/ProductEdit.vue";

export default defineComponent({
    async setup() {
        const emitter = useEmitter();
        const productType = ref("all-products");

        const products = useProducts(productType);
        const details = reactive({ product: {} });

        await products.fetchProducts();
        const list = products.products;
        const page = products.page;
        const totalPages = products.totalPages;
        const columns = [
            { key: "slug", sortable: true },
            { key: "price", sortable: true },
            { key: "user" },
            { key: "status", sortable: true },
            { key: "id" },
        ];

        function creatorPrev(user) {
            console.log(user);
            emitter.emit("modal/open", { view: UserPreviewVue, data: user });
        }

        function productPrev(index) {
            details.product = products.products.value.find(
                (pr) => pr.id == index
            );
            console.log(details);
            emitter.emit("modal/open", { view: ProductEditVue, data: details });
        }

        const selectedItems = ref([]);
        const rowDetails = ref({});
        return {
            products,
            productType,
            list,
            page,
            columns,
            totalPages,
            creatorPrev,
            productPrev,
            selectedItems,
            rowDetails,
        };
    },
});
</script>

<style lang="scss" scoped>
.datable {
    max-width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>