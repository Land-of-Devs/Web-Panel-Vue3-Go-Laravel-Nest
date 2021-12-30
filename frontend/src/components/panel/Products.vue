<template>
    <select @change="changeType($event)">
        <option value="all-products">1</option>
        <option value="my-products">2</option>
    </select>

    <div class="datable">
        <va-data-table
            :items="list"
            :columns="columns"
            :current-page="page"
            :selectable="true"
            :loading="!list.length"
            @filtered="filtered = $event.items"
        >
        </va-data-table>
    </div>
    <va-pagination v-model="page" input :pages="totalPages" />
</template>

<script>
import { useProducts } from "/src/composables/useProducts";
import { defineComponent, ref } from "vue";

export default defineComponent({
    async setup() {
        const productType = ref("all-products");
        const products = useProducts(productType);
        await products.fetchProducts();
        const list = products.products;
        console.log(list)
        const page = products.page;
        const totalPages = products.totalPages;
        const columns = [
            {key: 'id', sortable: true},
            {key: 'slug', sortable: true},
            {key: 'price', sortable: true},
            {key: 'creator'},
            {key: 'status', sortable: true},
            {key: 'actions'}
                ];
        function changeType(event) {
            productType.value = event.target.value;
        }

        return {
            products,
            productType,
            changeType,
            list,
            page,
            columns,
            totalPages,
        };
    },
});
</script>

<style lang="scss" scoped>
.datable {
    max-width: 100%;
}
</style>