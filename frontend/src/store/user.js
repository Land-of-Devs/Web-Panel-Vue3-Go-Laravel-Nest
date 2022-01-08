import { getCookieJson } from "../utils/cookie";

export const userRole = 1;
export const employeeRole = 2;
export const adminRole = 3;

const udata_guest = {
  role: 0
}

export const userStore = {
  namespaced: true,
  state: getCookieJson('userdata') || udata_guest,
  mutations: {
    SETUSER(state, user) {
      if (user == null) {
        for (const [k] of Object.entries(state)) {
          delete state[k];
        }

        user = udata_guest;
      }

      for (const [k, v] of Object.entries(user)) {
        state[k] = v;
      }
    }
  },
  actions: {
    setUser(state, user) {
      state.commit("SETUSER", user);
    }
  },
  getters: {
    getRole(state) {
      return state.role;
    }
  }
}
