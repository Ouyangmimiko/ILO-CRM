import { createStore } from 'vuex'

export default createStore({
  state: {
    isLoading: false,
    isAuthenticated: false,
    token: ''
  },
  getters: {
  },
  mutations: {
    // Configure the Vuex store
    initializeStore(state) {
      const token = localStorage.getItem('token');
      if (token !== null) {
        state.token = token;
        state.isAuthenticated = true;
      } else {
        state.token = '';
        state.isAuthenticated = false;
      } 
    },
    setIsLoading(state, status) {
      state.isLoading = status;
    },
    setToken(state, token) {
      state.token = token;
      state.isAuthenticated = true;
    },
    removeToken(state) {
      state.token = '';
      state.isAuthenticated = false;
    }
  },
  actions: {
  },
  modules: {
  }
})
