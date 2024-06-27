<template>
    <div>
      
      <!--<table>
        <thead>
          <tr>
            <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row, rowIndex) in data" :key="rowIndex">
            <td v-for="(cell, cellIndex) in row" :key="cellIndex">{{ cell }}</td>
          </tr>
        </tbody>
      </table>-->
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
    'Project client 2021',
    'Project client 2022',
    'Project client 2023'
  ]);*/
  //a example data for subtable
  const PTable = ref<any[]>([
    {
        'ORGANISATION': '',
        'ORGANISATION SECTOR': '', 
        'FIRST NAME': '', 
        'SURNAME': '', 
        'JOB TITLE': '', 
        'EMAIL ADDRESS': '', 
        'LOCATION': '', 
        'UoB ALUMNI?': '', 
        'Programme of study engaged': 'UG Computer Science', 
        'Project client 2021': '',
        'Project client 2022': '',
        'Project client 2023': 'SEP client'
    }
  ]);
  
  const emit = defineEmits(['sendData']);

  const sendData = () => {
    emit('sendData', PTable.value);
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
  