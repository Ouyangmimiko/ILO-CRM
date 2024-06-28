<template>
  <MainNavbar />
  <!--<nav>
    <router-link to="/">Home</router-link> |
    <router-link to="/about">About</router-link>
  </nav>-->
  <router-view/>
</template>

<script setup>
  import MainNavbar from '@/components/layout/MainNavbar';
</script>

<style lang="scss">
@import '../node_modules/bulma';
</style>
