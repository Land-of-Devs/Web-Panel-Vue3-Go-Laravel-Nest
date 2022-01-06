import * as productService from '../services/products';
import { ref, watch, reactive } from 'vue';
//import { useRoute } from 'vue-router'

export function useProducts(type) {

    //------[ VARS ]------\\
    const products = ref([]);
    const productCount = ref(0);
    const details = reactive({ product: {}, new: {} });
    const page = ref(1);
    const status = ref('All');
    const totalPages = ref(1);
    const loading = ref(false);

    //------[ PRODUCT LIST ]------\\
    async function fetchProducts() {

        products.value = [];
        let responsePromise;
        switch (type.value) {
            case 'my-products':
                responsePromise = await productService.myList(page.value, status.value !== 'All' ? status.value : null);
                break;
            case 'all-products':
                responsePromise = await productService.staffList(page.value, status.value !== 'All' ? status.value : null);
                break;
            case 'client-products':
                responsePromise = await productService.clientList(page.value, status.value !== 'All' ? status.value : null);
                break;
        }

        if (responsePromise !== null) {
            const response = responsePromise;
            products.value = response.list;
            productCount.value = response.total;
            totalPages.value = response.totalPages;
            loading.value = false;
        } else {
            throw new Error(`This option list doesn't exist!`);
        }
    }

    //------[ ACTIONS ]------\\

    //Update
    const updateProduct = async (slug, form) => {
        let updatedProduct = await productService.update(slug, form);
        if (updatedProduct) {
            let index = products.value.findIndex(i => i.slug == slug);
            products.value[index] = updatedProduct.product;
            Object.assign(details.product, updatedProduct.product);
        }
    };

    //Create
    const createProduct = async (obj) => {
        let newProduct = await productService.create(obj);
        if (newProduct) {
            products.value.unshift(newProduct.product);
        }
    }

    //Set Status
    // const statusProduct = (indexs, status) => {
    //     //if peticion success
    //     //then
    //     //for index in index products.value[index].status = status
    // }

    //------[ WATCHERS AND FUNC ]------\\
    const newData = async () => {
        loading.value = true;
        if (page.value !== 1) changePage(1)
        else await fetchProducts()
    };

    const changePage = (num) => {
        page.value = num;
    };

    watch(type, newData);

    watch(page, fetchProducts);

    watch(status, newData);

    return {
        fetchProducts,
        products,
        productCount,
        page,
        totalPages,
        changePage,
        updateProduct,
        details,
        createProduct,
        status
    };
}