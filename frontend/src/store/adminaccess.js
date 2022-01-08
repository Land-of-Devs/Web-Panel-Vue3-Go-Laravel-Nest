import { getCookieJson } from "../utils/cookie";

export const adminAccessStore = {
  namespaced: true,
  state: {until: getCookieJson('adminaccess') || 0},
  mutations: {
    SETUNTIL(state, until) {
      state.until = until;
    }
  },
  actions: {
    setUntil(state, until) {
      state.commit("SETUNTIL", +until);
    }
  },
  getters: {
    getUntil(state) {
      return state.until;
    }
  }
};
