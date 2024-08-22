<template>
<div>
  <h3>Config the year selector </h3>
  <el-form style="margin-top: 20px">
    <el-form-item label="Default End Year Range">
      <el-input-number v-model="endYearRange" :min="1" :max="10" @change="changeEndYear" />
    </el-form-item>
    <el-form-item label="Default Last Year Range">
      <el-input-number v-model="lastYearRange" :min="5" :max="30" @change="changeLastYear" />
    </el-form-item>
  </el-form>
</div>
</template>

<script lang="ts" setup>
import { ref } from "vue";

const endYearRange = ref<number>(0);
const lastYearRange = ref<number>(0);

if (localStorage.getItem('betweenRange')) {
  endYearRange.value = Number(localStorage.getItem("betweenRange"));
}
if (localStorage.getItem('lastYearRange')) {
  lastYearRange.value = Number(localStorage.getItem('lastYearRange'));
}

const changeEndYear = () => {
  localStorage.setItem('betweenRange', endYearRange.value.toString());
}

const changeLastYear = () => {
  localStorage.setItem('lastYearRange', lastYearRange.value.toString());
}

</script>

<style scoped>

</style>