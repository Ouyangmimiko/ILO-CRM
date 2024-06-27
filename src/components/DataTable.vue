<template>
    <div>
      <div class="table-container">
        <table>
            <thead>
                <tr>
                   <th v-for="(header, index) in combinedHeaders" :key="index">{{ header }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, rowIndex) in combinedData" :key="rowIndex">
                    <td v-for="(header, index) in combinedHeaders" :key="index">{{ row[header] }}</td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
  </template>
  
<script setup lang="ts">
import { ref } from 'vue';

const combinedHeaders = ref<string[]>([]);
const combinedData = ref<any[]>([]);

const receiveData = (data: any[]) => {
  data.forEach(row => {
    Object.keys(row).forEach(key => {
      if (!combinedHeaders.value.includes(key)) {
        combinedHeaders.value.push(key);
      }
    });
  });

  data.forEach(row => {
    // 以organisation作为每行数据是否重复的标准，后续需添加更多条件
    const existingRow = combinedData.value.find(r => r['ORGANISATION'] === row['ORGANISATION']);
    if (!existingRow) {
      const newRow: any = {};
      combinedHeaders.value.forEach(header => {
        newRow[header] = row[header] || '';
      });
      combinedData.value.push(newRow);
    } else {
      combinedHeaders.value.forEach(header => {
        if (!existingRow[header]) {
          existingRow[header] = row[header] || '';
        }
      });
    }
  });
};

defineExpose({
  receiveData
});
</script>
  
  <style scoped>
  .table-container {
    overflow-x: auto;
  }
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
  