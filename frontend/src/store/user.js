export const userRole = 1;
export const employeeRole = 2;
export const adminRole = 3;

let udata = localStorage.getItem('userData');
const udata_guest = {
  role: 0
}

try {
  udata = udata ? JSON.parse(udata) : udata_guest;
} catch (e) {
  udata = udata_guest;
}

if (udata_guest == udata) {
  localStorage.setItem('userData', JSON.stringify(udata));
}

export const userStore = {
  namespaced: true,
  state: udata,
  mutations: {
    SETUSER(state, user) {
      for (const [k, v] of Object.entries(user)) {
        state[k] = v;
      }
    }
  },
  actions: {
    setUser(state, user) {
      localStorage.setItem('userData', JSON.stringify(user));
      state.commit("SETUSER", user);
    }
  },
  getters: {
    getRole(state) {
      return state.role;
    }
  }
}
