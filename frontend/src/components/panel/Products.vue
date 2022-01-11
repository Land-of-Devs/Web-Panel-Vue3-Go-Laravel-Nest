<template>
    <va-card class="action mb-2">
        <va-card-title>Product List</va-card-title>
        <va-card-content>
            <div class="tab-action row">
                <div class="flex xs12 pb-0 pt-0">
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
                </div>
                <div class="selector flex xs9 md10 lg4">
                    <va-select
                        label="Status"
                        v-model="status"
                        class="flex pt-0 pb-0 pl-0 pr-0"
                        :options="[
                            'All',
                            'Pending',
                            'Accepted',
                            'Cancelled',
                            'Complete',
                        ]"
                    />
                </div>
                <div
                    class="flex flex-center xs3 md2 lg2 offset--lg6 btn-actions"
                >
                    <va-button color="success" gradient @click="productCrt()">
                        <va-icon name="note_add" />
                    </va-button>
                </div>
            </div>
            <div class="row" v-if="hasRole(3) && !adminAccess">
                <div class="flex xs6 flex-center text--bold">
                    Admin features are disabled.
                </div>
                <div class="flex xs6 flex-center">
                    <va-button gradient @click="getAdminAccess()">
                        Enable admin
                    </va-button>
                </div>
            </div>
            <Selected
                v-if="selectedItems.length > 0 && adminAccess"
                v-on:confirm="selectAction($event)"
                :selected="selected"
            />
        </va-card-content>
    </va-card>
    <va-card class="datable">
        <va-card-content>
            <va-data-table
                :items="list"
                :columns="columns"
                :current-page="page"
                :selectable="!!adminAccess"
                v-model="selectedItems"
                :clickable="true"
                :loading="loading"
                :striped="true"
            >
                <template #header(user)>Creator</template>
                <template #header(id)>Actions</template>
                <template #header(image)>Product</template>
                <template #cell(image)="{ source: image }">
                    <va-avatar
                        square
                        :src="'/api/data/img/products/' + image"
                    />
                </template>
                <template #cell(price)="{ source: price }"
                    >{{ price }}€</template
                >
                <template #cell(user)="{ source: user }">
                    <va-button
                        v-if="user && user.username"
                        color="#ffac0a"
                        gradient
                        @click="creatorPrev(user)"
                        >{{ user && user.username }}</va-button
                    >
                    <div v-else>None</div>
                </template>
                <template #cell(status)="{ source: status }">
                    <StatusBadge :status="status" />
                </template>
                <template #cell(id)="{ source: id }">
                    <va-button color="primary" gradient @click="productPrev(id)"
                        ><va-icon name="preview"
                    /></va-button>
                    <va-button
                        color="danger"
                        gradient
                        v-if="hasRole(3)"
                        :disabled="!adminAccess"
                        @click="del(id)"
                        ><va-icon name="delete"
                    /></va-button>
                </template>
            </va-data-table>
        </va-card-content>
        <va-card-actions align="center">
            <va-pagination
                v-model="page"
                input
                :pages="totalPages"
                :per-page="list.length"
            />
        </va-card-actions>
    </va-card>
</template>

<script>
import * as validator from "/src/utils/validator";
import { useProducts } from "/src/composables/useProducts";
import { defineComponent, ref, toRefs, watch, computed } from "vue";
import useEmitter from "/src/composables/useEmitter";
import UserPreviewVue from "./modals/UserPreview.vue";
import ProductEditVue from "./modals/ProductEdit.vue";
import ProductCreateVue from "./modals/ProductCreate.vue";
import Selected from "/src/components/global/shared/Selected.vue";
import StatusBadge from "/src/components/global/shared/StatusBadge";
import { useRoute, useRouter } from "vue-router";
import { hasAdminAccess, hasRole } from "../../utils/store";

export default defineComponent({
    components: {
        Selected,
        StatusBadge,
    },
    async setup() {
        const $router = useRouter();
        const $route = useRoute();
        const adminAccess = computed(hasAdminAccess);
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
            { key: "image", verticalAlign: "middle" },
            { key: "slug", sortable: true, verticalAlign: "middle" },
            { key: "user", verticalAlign: "middle" },
            { key: "price", sortable: true, verticalAlign: "middle" },
            { key: "status", sortable: true, verticalAlign: "middle" },
            { key: "id", verticalAlign: "middle" },
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
            let index = list.value.findIndex((i) => i.id == id);
            let slug = list.value[index].slug;
            await products.deleteProducts([slug]);
        }

        function getAdminAccess() {
            $router.replace('/panel/admin-access?to=' + encodeURIComponent($route.fullPath));
        }

        return {
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
            adminAccess,
            getAdminAccess,
            hasRole
        };
    },
});
</script>
