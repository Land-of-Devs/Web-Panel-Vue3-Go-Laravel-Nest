<template>
  <va-button @click="newTicket()" class="mr-4 mt-2 align-self--end" icon="confirmation_number">
  </va-button>
  <div class="list">
    <ProductElement v-for="p in products" :key="p.slug" :data="p"></ProductElement>
    
  </div>
  <va-pagination class="pagination" v-model="page" :pages="totalPages" />
</template>

<script>
import { ref } from 'vue';
import { useProducts } from '../composables/useProducts'
import ProductElement from '../components/shop/ProductElement.vue'
import useEmitter from '../composables/useEmitter';
import RequestProductVue from '../components/shop/modal/tickets/RequestProduct.vue';
//import useEmitter from '../composables/useEmitter';
//import ProductPreviewVue from '../components/shop/modal/ProductPreview.vue';

export default {
  components: {
    ProductElement
  },
  setup() {
    const product = useProducts(ref('client-products'));
    product.fetchProducts();
    const { products, totalPages, page } = product;
    const emitter = useEmitter();

    function newTicket() {
      emitter.emit('modal/open', {view: RequestProductVue})
    }

    return {
      products,
      totalPages,
      page,
      newTicket
      //onView
    }
  }
}
</script>

<style lang="scss" scoped>
.list {
  padding: 20px;
  height: 80%;
  box-sizing: border-box;
  display: flex;
  flex-wrap: wrap;
  overflow: auto;

}
.pagination {
  align-self: center;
  margin: 20px;
}
</style>
