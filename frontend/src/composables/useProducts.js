import * as productService from '../services/products';
import { ref, watch, reactive } from 'vue';
//import { useRoute } from 'vue-router'

export function useProducts(type) {
    const products = ref([]);
    const productCount = ref(0);
    const details = reactive({ product: {}, new: {} });
    const page = ref(1);
    const totalPages = ref(1);

    async function fetchProducts() {

        products.value = [];
        let responsePromise;

        switch (type.value) {
            case 'my-products':
                responsePromise = await productService.myList();
                break;
            case 'all-products':
                responsePromise = await productService.staffList();
                break;
            case 'client-products':
                responsePromise = await productService.clientList();
                break;
        }

        if (responsePromise !== null) {
            const response = responsePromise;
            products.value = response.list;
            productCount.value = response.total;
            totalPages.value = response.totalPages;
        } else {
            throw new Error(`This option list doesn't exist!`);
        }
    }

    const changePage = (num) => {
        page.value = num;
    };

    const updateProduct = async (slug, form) => {
        let updatedProduct = await productService.update(slug, form);
        if (updatedProduct) {
            let index = products.value.findIndex(i => i.slug == slug);
            products.value[index] = updatedProduct.product;
            Object.assign(details.product, updatedProduct.product);
        }
    };


    const createProduct = async (obj) => {
        let newProduct = await productService.create(obj);
        if (newProduct) {
            products.value.push(newProduct.product);
        }
    }


    const newData = async () => {
        if (page.value !== 1) changePage(1)
        else await fetchProducts()
    };

    // const statusProduct = (indexs, status) => {
    //     //if peticion success
    //     //then
    //     //for index in index products.value[index].status = status
    // }

    watch(type, newData);

    watch(page, fetchProducts);

    // watch(status, newData);

    return {
        fetchProducts,
        products,
        productCount,
        page,
        totalPages,
        changePage,
        updateProduct,
        details,
        createProduct
    };
}