<template>
  <va-card>
    <va-card-title>Creation statistics</va-card-title>
    <va-card-content>
      <div class="row">
        <div class="flex md6 xs12">
          <va-date-input label="From" v-model="dates.fromDate" />
        </div>
        <div class="flex md6 xs12">
          <va-date-input label="To" v-model="dates.toDate" />
        </div>
      </div>
      <div class="row" v-if="hasRole(3) && !adminAccess">
        <div class="flex xs6 flex-center text--bold">Admin stats are hidden.</div>
        <div class="flex xs6 flex-center">
          <va-button gradient @click="getAdminAccess()">View admin stats</va-button>
        </div>
      </div>
    </va-card-content>
  </va-card>
  <div class="row">
    <div class="flex md6 xs12">
      <va-card class="chart-widget" title="Tickets">
        <va-card-content>
          <va-chart :data="ticketsChartData" type="line" />
        </va-card-content>
      </va-card>
    </div>
    <div class="flex md6 xs12">
      <va-card class="chart-widget" title="Products" v-if="adminAccess">
        <va-card-content>
          <va-chart :data="productsChartData" type="line" />
        </va-card-content>
      </va-card>
    </div>
  </div>
  <div class="row">
    <div class="flex md12 xs12">
      <va-card class="chart-widget" title="Users" v-if="adminAccess">
        <va-card-content>
          <va-chart :data="usersChartData" type="line" />
        </va-card-content>
      </va-card>
    </div>
  </div>
  <va-card v-if="adminAccess">
    <va-card-title>Totals statistics</va-card-title>
    <va-card-content>
      <div class="row">
        <div class="flex md6 offset--md3 xs12">
          <va-date-input
            type="year"
            :format="date => date ? date.getFullYear() : 'All time'"
            label="From"
            v-model="totals.year"
            clearable
          />
        </div>
      </div>
    </va-card-content>
  </va-card>
  <div class="row" v-if="adminAccess">
    <div class="flex xs12">
      <va-card class="chart-widget" title="Totals">
        <va-card-content>
          <va-chart :data="totalsChartData" type="vertical-bar" />
        </va-card-content>
      </va-card>
    </div>
  </div>
</template>


<script>
import VaChart from '/src/components/va-charts/VaChart';
import { computed, defineComponent, reactive, ref, watch } from "vue";
import { useStatistics } from "../../composables/useStatistics";
import { toJSONDateString } from "../../utils/formatter";
import { offsetDate } from '../../utils/date';
import { useRoute, useRouter } from 'vue-router';
import { hasAdminAccess, hasRole } from '../../utils/store';

export default defineComponent({
  components: { VaChart },
  async setup() {
    const $router = useRouter();
    const $route = useRoute();

    const stats = useStatistics();

    const adminAccess = computed(hasAdminAccess);

    const ticketsChartData = ref({});
    const productsChartData = ref({});
    const usersChartData = ref({});
    const totalsChartData = ref({});

    const dates = reactive({
      fromDate: stats.defaultFromDate,
      toDate: offsetDate(new Date(stats.defaultToDate), 1000 * 60 * 60 * 24 * -1)
    });

    const totals = reactive({
      year: null
    });

    watch(dates, () => {
      const from = toJSONDateString(offsetDate(dates.fromDate, 1000 * 60 * 60 * 24));
      const to = toJSONDateString(offsetDate(dates.toDate, 1000 * 60 * 60 * 24 * 2));

      stats.products.form.from_date = from;
      stats.tickets.form.from_date = from;
      stats.users.form.from_date = from;

      stats.products.form.to_date = to;
      stats.tickets.form.to_date = to;
      stats.users.form.to_date = to;
    });

    watch(totals, () => {
      if (totals.year !== undefined) {
        const year = totals.year.getFullYear();
        stats.totalProducts.form.year = year;
        stats.totalUsers.form.year = year;
      } else {
        delete stats.totalProducts.form.year;
        delete stats.totalUsers.form.year;
      }
    });

    function generateDateRanges(from, to, step = 1000 * 60 * 60 * 24 /* 1 day */) {
      const labels = [];
      from = new Date(from).getTime();
      to = new Date(to).getTime();

      do {
        labels.push(toJSONDateString(new Date(from)));
        from += step;
      } while (from < to);

      return labels;
    }

    function statsToDateRangeVerticalBarChart(stats, label, clr = '#154ec1') {
      return {
        labels: generateDateRanges(stats.form.from_date, stats.form.to_date),
        datasets: [
          {
            label,
            backgroundColor: clr,
            borderColor: 'transparent',
            data: stats.list
          }
        ]
      };
    }

    function statsToTotalsVerticalBarChart(allStats, label, clr = '#c14ec1') {
      return {
        labels: allStats.map(e => e.label),
        datasets: [
          {
            label,
            backgroundColor: clr,
            borderColor: 'transparent',
            data: allStats.map(e => e.stats.total)
          }
        ]
      };
    }


    watch(stats.tickets, () => {
      ticketsChartData.value = statsToDateRangeVerticalBarChart(stats.tickets, 'Tickets created');
    });

    watch(stats.products, () => {
      productsChartData.value = statsToDateRangeVerticalBarChart(stats.products, 'Products created', '#c14e15');
    });

    watch(stats.users, () => {
      usersChartData.value = statsToDateRangeVerticalBarChart(stats.users, 'Users created', '#15c14e');
    });

    watch(stats.users, () => {
      usersChartData.value = statsToDateRangeVerticalBarChart(stats.users, 'Users created', '#15c14e');
    });

    const reloadTotalsChart = () => {
      totalsChartData.value = statsToTotalsVerticalBarChart([
        {stats: stats.totalProducts, label: 'Products'},
        {stats: stats.totalUsers, label: 'Users'}
      ], totals.year ? 'Totals for ' + totals.year.getFullYear() : 'Totals of all time');
    };

    watch(stats.totalProducts, reloadTotalsChart);
    watch(stats.totalUsers, reloadTotalsChart);

    await stats.tickets.fetch();

    if (adminAccess.value) {
      await stats.products.fetch();
      await stats.users.fetch();

      await stats.totalProducts.fetch();
      await stats.totalUsers.fetch();
    }

    function getAdminAccess() {
      $router.replace('/panel/admin-access?to=' + encodeURIComponent($route.fullPath));
    }

    return {
      dates,
      totals,
      ticketsChartData,
      productsChartData,
      usersChartData,
      totalsChartData,
      adminAccess,
      getAdminAccess,
      hasRole
    }
  }
});
</script>