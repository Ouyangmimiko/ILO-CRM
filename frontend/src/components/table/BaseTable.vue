<template>
    <table class="table is-bordered">
      <thead>
        <tr>
          <th v-for="header in headers" :key="header">{{ header }}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows" :key="row.id">
          <td v-for="header in headers" :key="header">{{ row[header] }}</td>
          <td>
            <div><a>details</a></div>
            <div><a>modify</a></div>
          </td>
        </tr>
      </tbody>
    </table>
</template>
  
<script setup>
  const props = defineProps({
  headers: {
    type: Array
  },
  rows: {
    type: Array
  }
 });
</script>
  