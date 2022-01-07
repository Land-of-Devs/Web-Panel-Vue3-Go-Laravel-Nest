<template>
    <div class="action">
        <div class="tab-action">
            <va-tabs
                class="list-type"
                v-model="productType"
                :grow="true"
                :hide-slider="true"
            >
                <template #tabs>
                    <va-tab name="all-products"> All Products </va-tab>
                    <va-tab name="my-products"> My Products </va-tab>
                </template>
            </va-tabs>
            <div class="selector">
                <va-select
                    label="Status"
                    v-model="status"
                    class="flex"
                    :options="[
                        'All',
                        'Pending',
                        'Accepted',
                        'Cancelled',
                        'Complete',
                    ]"
                />
            </div>
            <div class="btn-actions">
                <va-button color="success" gradient @click="productCrt()">
                    <va-icon name="note_add" />
                </va-button>
            </div>
        </div>
        <div class="selected">
            <Selected
                v-if="selectedItems.length > 0 && role == 3"
                v-on:confirm="selectAction($event)"
                :selected="selected"
            />
        </div>
    </div>
    <div class="datable">
        <va-data-table
            :items="list"
            :columns="columns"
            :current-page="page"
            :selectable="role == 3"
            v-model="selectedItems"
            :clickable="true"
            :loading="loading"
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
                <va-button color="danger" gradient v-if="role == 3"
                @click="del(id)"
                    ><va-icon name="delete"
                /></va-button>
            </template>
        </va-data-table>
        <va-pagination
            v-model="page"
            input
            :pages="totalPages"
            :per-page="list.length"
        />
    </div>
</template>

<script>
import * as validator from "/src/utils/validator";
import { useProducts } from "/src/composables/useProducts";
import { defineComponent, ref, toRefs, watch, computed } from "vue";
import useEmitter from "/src/composables/useEmitter";
import UserPreviewVue from "./modals/UserPreview.vue";
import ProductEditVue from "./modals/ProductEdit.vue";
import ProductCreateVue from "./modals/ProductCreate.vue";
import Selected from "./shared/Selected.vue";
import { useStore } from "vuex";

export default defineComponent({
    components: {
        Selected,
    },
    async setup() {
        const store = useStore();
        const role = computed(() => store.getters["user/getRole"]);
        const val = validator;
        const emitter = useEmitter();
        const productType = ref("all-products");
        const products = useProducts(productType);
        const status = ref(products.status);
        const loading = ref(products.loading);
        const details = toRefs(products.details);
        const newProduct = ref({});
        const selected = ref({
            list: ["None", "Delete", "Status"],
            values: {
                Status: [
                    "None",
                    "Pending",
                    "Accepted",
                    "Cancelled",
                    "Complete",
                ],
            },
        });

        watch(
            () => details.new,
            async (newVal) => {
                if (Object.keys(newVal.value).length > 0) {
                    await products.updateProduct(
                        details.product.value["slug"],
                        val.create(newVal.value)
                    );
                }
            },
            { deep: true }
        );

        watch(
            () => newProduct,
            async (newVal) => {
                if (Object.keys(newVal.value).length > 0) {
                    await products.createProduct(val.create(newProduct.value));
                    Object.assign(newProduct, {});
                }
            },
            { deep: true }
        );

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
            emitter.emit("modal/open", { view: UserPreviewVue, data: user });
        }

        function productPrev(index) {
            details.product.value = list.value.find((pr) => pr.id == index);
            emitter.emit("modal/open", { view: ProductEditVue, data: details });
        }

        function productCrt() {
            emitter.emit("modal/open", {
                view: ProductCreateVue,
                data: newProduct,
            });
        }

        const selectedItems = ref([]);
        async function selectAction(action) {
            let newItemsKey = selectedItems.value.map(({ slug }) => slug);
            if (action.option === "Status") {
                await products.statusProducts(newItemsKey, action.value);
            } else {
                await products.deleteProducts(newItemsKey);
            }
            selectedItems.value = [];
        }

        async function del(id) {
            let index = list.value.findIndex(i => i.id == id);
            let slug = list.value[index].slug;
            await products.deleteProducts([slug]);
        }

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
            productCrt,
            status,
            loading,
            selected,
            selectAction,
            del,
            role
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
.action {
    .tab-action {
        margin-top: 20px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        .list-type {
            width: 40%;
        }
        .selector {
        }
        .btn-actions {
            margin-right: 10px;
        }
    }
    .selected {
        display: flex;
        justify-content: space-evenly;
    }
}
</style>