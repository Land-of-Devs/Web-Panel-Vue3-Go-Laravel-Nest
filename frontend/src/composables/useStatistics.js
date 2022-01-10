import { reactive, watch } from "vue";
import * as statsApi from "../services/stats";
import { toJSONDateString } from "../utils/formatter";

const now = new Date((new Date).getTime() + 1000 * 60 * 60 * 24);
const defaultFromDate = new Date(now.getTime() - 1000 * 60 * 60 * 24 * 10); /* 10 days ago */

function makeRangedStatsContext(fetchFn) {
  const state = reactive({
    list: [],
    form: {
      from_date: toJSONDateString(defaultFromDate),
      to_date: toJSONDateString(now)
    },
    error: false,
    loading: false,
    enabled: false,
    async fetch() {
      if (!this.enabled) {
        watch(this.form, () => this.fetch());
        this.enabled = true;
      }

      try {
        this.loading = true;
        const { stats } = await fetchFn(this.form);

        this.list = stats;

        this.error = false;
      } catch (e) {
        this.error = true;
      } finally {
        this.loading = false;
      }
    }
  });

  return state;
}

function makeYearTotalsStatsContext(fetchFn) {
  const state = reactive({
    total: 0,
    form: {},
    error: false,
    loading: false,
    enabled: false,
    async fetch() {
      if (!this.enabled) {
        watch(this.form, () => this.fetch());
        this.enabled = true;
      }

      try {
        this.loading = true;
        const { total } = await fetchFn(this.form);

        this.total = total;

        this.error = false;
      } catch (e) {
        this.error = true;
      } finally {
        this.loading = false;
      }
    }
  });

  return state;
}

export function useStatistics() {
  const products = makeRangedStatsContext(statsApi.createdProducts);
  const tickets = makeRangedStatsContext(statsApi.createdTickets);
  const users = makeRangedStatsContext(statsApi.createdUsers);

  const totalProducts = makeYearTotalsStatsContext(statsApi.totalCreatedProducts);
  const totalUsers = makeYearTotalsStatsContext(statsApi.totalCreatedUsers);

  return {
    defaultToDate: now,
    defaultFromDate,
    products,
    tickets,
    users,
    totalProducts,
    totalUsers
  };
}