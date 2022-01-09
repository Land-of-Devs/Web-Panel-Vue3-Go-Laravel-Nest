<template>
  <div class="row">
    <div class="flex md6 xs12">
      <va-card class="chart-widget" :title="Tickets">
        <va-card-content>
          <va-chart :data="stats.tickets.list" type="line" />
        </va-card-content>
      </va-card>
    </div>
    <div class="flex md6 xs12">
      <va-card class="chart-widget" :title="Products">
        <va-card-content>
          <va-chart :data="stats.products.list" type="line" />
        </va-card-content>
      </va-card>
    </div>
  </div>
  <div class="row">
    <div class="flex md12 xs12">
      <va-card class="chart-widget" :title="Users">
        <va-card-content>
          <va-chart :data="stats.users.list" type="line" />
        </va-card-content>
      </va-card>
    </div>
  </div>
</template>


<script>
//import VaChart from '@/components/va-charts/VaChart';
import { defineComponent } from "vue";
import { useStore } from "vuex";
import { useStatistics } from "../../composables/useStatistics";

export default defineComponent({
  //components: { VaChart },
  setup() {
    const stats = useStatistics();
    const store = useStore();

    stats.products.fetch();
    stats.tickets.fetch();

    if (store.getters['user/getRole'] == 3 && store.getters['adminaccess/getUntil']) {
      stats.users.fetch();
    }

    return {
      stats,
      store
    }
  }
});
</script>