<template>
  <div>
    <MainNavbar />

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
</style>
