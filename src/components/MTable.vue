<template>
    <div>
      
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, defineEmits } from 'vue';
  
  /*const headers = ref<string[]>([
    'ORGANISATION', 
    'ORGANISATION SECTOR', 
    'FIRST NAME', 
    'SURNAME', 
    'JOB TITLE', 
    'EMAIL ADDRESS', 
    'LOCATION', 
    'UoB ALUMNI?', 
    'Programme of study engaged', 
    'Mentor Prior to 2020',
    'Mentor 2021/22',
    'Mentor 2022/23',
    'Mentor 2023/24'
  ]);*/
  // a example data for subtable
  const MTable = ref<any[]>([
    {
        'ORGANISATION': 'Schlumberger (SLB)',
        'ORGANISATION SECTOR': '', 
        'FIRST NAME': '', 
        'SURNAME': '', 
        'JOB TITLE': '', 
        'EMAIL ADDRESS': '', 
        'LOCATION': 'Bristol (and surrounding area)', 
        'UoB ALUMNI?': 'Yes', 
        'Programme of study engaged': 'EEENG', 
        'Mentor Prior to 2020': '',
        'Mentor 2021/22': '',
        'Mentor 2022/23': 'Y',
        'Mentor 2023/24': ''
    }
    
  ]);
  
  const emit = defineEmits(['sendData']);

  const sendData = () => {
    emit('sendData', MTable.value);
  };

  defineExpose({
    sendData
  });
  // No need to use onMounted for static data
  </script>
  
  <style scoped>
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  th {
    background-color: #f2f2f2;
  }
  </style>
  