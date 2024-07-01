<template>
  <div id="app">
    <MainNavbar />

    <!--content-->
    <section class="section">
      <router-view/>
    </section>
  </div>
</template>

<script setup>
  import MainNavbar from '@/components/layout/MainNavbar';

  // Configure the Vuex store
  import { onBeforeMount } from 'vue';
  import axios from 'axios';
  import { useStore } from 'vuex';

  const store = useStore();

  onBeforeMount(() => {
    store.commit('initializeStore')

    if (store.state.token) {
      axios.defaults.headers.common['Authorization'] = "Token " + store.state.token;
    } else {
      axios.defaults.headers.common['Authorization'] = "";
    }
  });
</script>

<style lang="scss">
@import '../node_modules/bulma';

// loading bar
/*.lds-dual-ring {
  display: inline-block;
  width: 80px;
  height: 80px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 64px;
  height: 64px;
  margin: 8px;
  border-radius: 50%;
  border: 6px solid #ccc;
  border-color: #ccc transparent #ccc transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
.is-loading-bar {
  height: 0;
  overflow: hidden;
  -webkit-transform: all 0.3s;
  transition: all 0.3s;

  &.is-loading {
    height: 80px;
  }
}*/
</style>
