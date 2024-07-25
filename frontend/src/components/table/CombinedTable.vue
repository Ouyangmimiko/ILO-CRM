<template>
    <div>
      <BaseTable :headers="headers" :rows="rows" v-if="headers.length && rows.length" />
      <p v-else>Loading...</p>
    </div>
</template>
  
<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import BaseTable from '@/components/table/BaseTable.vue';
  
  const headers = ref([]);
  const rows = ref([]);
  
  const fetchData = async () => {
    try {
      const response = await axios.get('/api/data');
      headers.value = response.data.headers;
      rows.value = response.data.rows;
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };
  
  onMounted(() => {
    fetchData();
  });
</script>
  