import { reactive, watch } from "vue";
import * as statsApi from "../services/stats";
import { toJSONDateString } from "../utils/formatter";

const now = new Date;
const defaultFromDate = new Date(now.getTime() - 1000 * 60 * 60 * 24 * 10); /* 10 days ago */

function makeStatsContext(fetchFn) {
  const state = reactive({
    list: [],
    form: {
      from_date: toJSONDateString(defaultFromDate),
      to_date: toJSONDateString(now)
    },
    error: false,
    loading: false,
    async fetch() {
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

  watch(state.form, () => state.fetch());

  return state;
}

export function useStatistics() {
  const products = makeStatsContext(statsApi.createdProducts);
  const tickets = makeStatsContext(statsApi.createdTickets);
  const users = makeStatsContext(statsApi.createdUsers);

  return {
    products,
    tickets,
    users
  };
}