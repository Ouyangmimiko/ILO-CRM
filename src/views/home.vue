<template>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

    <div>
        <section class="hero is-link">
            <div class="hero-body">
                <p class="title">ILO CRM</p>
                <p class="subtitle">Effortless Industry Engagement Management for Industry Liaison Office</p>
            </div>
        </section>

        <!--revoke main table-->
        <div id="masterDB">
            <h1>Master DataBase</h1>
            <button @click="sendAllData">Send All Data</button>
            <SubTable1 ref="subTable1" @sendData="receiveData"/>
            <SubTable2 ref="subTable2" @sendData="receiveData"/>
            <SubTable3 ref="subTable3" @sendData="receiveData"/>
            <DataTable ref="mainTable"/>
        </div>
    </div>

</template>

<script setup lang="ts">
import { ref } from 'vue';
import DataTable from '@/components/DataTable.vue';
import SubTable1 from '@/components/MTable.vue';
import SubTable2 from '@/components/YiITable.vue';
import SubTable3 from '@/components/PTable.vue';

const subTable1 = ref();
const subTable2 = ref();
const subTable3 = ref();
const mainTable = ref();

const sendAllData = () => {
  subTable1.value.sendData();
  subTable2.value.sendData();
  subTable3.value.sendData();
};

const receiveData = (data: any[]) => {
  mainTable.value.receiveData(data);
};
</script>

<style scoped>
    .title, .subtitle {
        line-height: 2em;
    }
</style>