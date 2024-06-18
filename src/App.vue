<template>
  <el-config-provider :locale="en">
    <router-view />
  </el-config-provider>
</template>

<script setup lang="ts">
import { ElConfigProvider } from "element-plus";
import { en } from "element-plus/es/locales.mjs";
</script>

<style>
@import "./assets/css/global.css";
</style>
