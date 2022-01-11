
<template>
  <div class="product-el xs12 sm6 md4 lg3 flex">
    <va-card class="card">
      <va-image
        :src="'/api/data/img/products/' + data.image"
        style="height: 200px; width: 100%;"
      />
      <va-card-title>{{ data.name }}</va-card-title>
      
      <va-card-content class="content">
        Autor: {{ data.user ? data.user.username : 'Ninguno' }}
        <b>{{ data.price  }}€</b>
        {{ data.description }}
        <va-button @click="onView(data)" icon="visibility">View</va-button>
        <va-button @click="onReport(data)" icon="flag" color="danger">Report</va-button>
      </va-card-content>
    </va-card>
  </div>
  
</template>


<script>
import useEmitter from '../../composables/useEmitter'
import ProductPreviewVue from './modal/ProductPreview.vue';
import ReportProductVue from './modal/tickets/ReportProduct.vue';
export default {
  props: ['data'], 
  emits: ['details'],
  setup() {

    const emitter = useEmitter();

    function onView(data) {
      emitter.emit('modal/open', {view: ProductPreviewVue, data: { product: data }});
    }

    function onReport(data) {
      emitter.emit('modal/open', {view: ReportProductVue, data: data });
    }

    return {
      onView,
      onReport
    }

  } 
}
</script>


<style lang="scss" scoped>
.product-el {
  padding: 20px;
  box-sizing: border-box;

  .card {
    overflow: hidden;
    border-radius: 10px;

    .content {
      display: flex;
      flex-direction: column;
      button {
        margin-top: 10px;
      }
    }
  }
  .view-btn {
    margin-left: auto;
  }
}
</style>