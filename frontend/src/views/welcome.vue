<template>
  <div>
    <div v-html="htmlContent"></div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';

const htmlContent = ref('');

onMounted(async () => {
  try {
    const response = await fetch('/app0A.html'); // 假设文件放在public目录下
    if (response.ok) {
      htmlContent.value = await response.text();
    } else {
      console.error('Failed to load HTML file:', response.statusText);
    }
  } catch (error) {
    console.error('Error fetching HTML file:', error);
  }
});
</script>

<style scoped></style>
