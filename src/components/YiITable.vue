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
    'Had Placements AY2021-22',
    'Had Placements AY2022-23',
    'Had Placements AY2023-24'
  ]);*/
  //a example data for subtable
  const YiITable = ref<any[]>([
    {
        'ORGANISATION': 'Advanced Manufacturing Research Centre (AMRC)',
        'ORGANISATION SECTOR': 'Other', 
        'FIRST NAME': '', 
        'SURNAME': '', 
        'JOB TITLE': '', 
        'EMAIL ADDRESS': '', 
        'LOCATION': 'Sheffield', 
        'UoB ALUMNI?': '', 
        'Programme of study engaged': 'MECH MECH ELEC AERO EDES', 
        'Had Placements AY2021-22': 'N',
        'Had Placements AY2022-23': 'N',
        'Had Placements AY2023-24': 'N'
    }
  ]);
  
  const emit = defineEmits(['sendData']);

  const sendData = () => {
    emit('sendData', YiITable.value);
  };

  defineExpose({
    sendData
  });
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
  