import { getCookieJson } from "../utils/cookie";

export const userRole = 1;
export const employeeRole = 2;
export const adminRole = 3;

const udata_guest = {
  role: 0
};

export const userStore = {
  namespaced: true,
  state: getCookieJson('userdata') || udata_guest,
  mutations: {
    SETUSER(state, user) {
      for (const [k, v] of Object.entries(user)) {
        state[k] = v;
      }
    },
    DELUSER(state) {
      for (const [k] of Object.entries(state)) {
        state[k] = udata_guest[k];
      }
    }
  },
  actions: {
    setUser(state, user) {
      if (user == null) {
        state.commit("DELUSER", user);
        user = udata_guest;
      }

      state.commit("SETUSER", user);
    }
  },
  getters: {
    getUser(state) {
      return state;
    },
    getRole(state) {
      return state.role;
    }
  }
}
