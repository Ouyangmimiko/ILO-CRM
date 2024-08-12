<template>
  <div class="wrapper">
    <v-header />
    <v-sidebar />
    <div class="content-box" :class="{ 'content-collapse': sidebar.collapse }">
      <v-tabs></v-tabs>
      <div class="content">
        <router-view v-slot="{ Component }">
          <transition name="move" mode="out-in">
            <keep-alive :include="tabs.nameList">
              <component :is="Component"></component>
            </keep-alive>
          </transition>
        </router-view>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useSidebarStore } from "../store/siderbar";
import { useTabsStore } from "../store/tabs";

import vHeader from "../components/header.vue";
import vSidebar from "../components/sidebar.vue";
import vTabs from "../components/tabs.vue";
import axios from "../api/axios";
import {AxiosError} from "axios";
import {ElMessage} from "element-plus";
import { useRouter } from "vue-router";
import {onMounted} from "vue";


const sidebar = useSidebarStore();
const tabs = useTabsStore();
const router = useRouter();
const checkLoginStatus = async () =>
{
  try {
    if (localStorage.getItem("ILO_user")) {
      await axios.get("/api/loginStatus");
    }
    // const response = await axios.get("/api/loginStatus");
    // console.log(response.data);
  } catch (error) {
    if (error instanceof AxiosError && error.response && error.response.data) {
      // if (error.response.data.error === 'Unauthorized') {
      //   await router.push("/login");
      // }
      localStorage.removeItem("ILO_user_name");
      localStorage.removeItem("auth_token");
      localStorage.removeItem("ILO_user");
      localStorage.removeItem("User_role");
      await router.push("/login");
    } else {
      ElMessage.error("An unexpected error occurred while getting users.");
    }
  }
}
onMounted(() => {
  checkLoginStatus();
  console.log('Tabs nameList:', tabs.nameList);
});
</script>

<style>
.wrapper {
  height: 100vh;
  overflow: hidden;
}
.content-box {
  position: absolute;
  left: 250px;
  right: 0;
  top: 70px;
  bottom: 0;
  padding-bottom: 30px;
  -webkit-transition: left 0.3s ease-in-out;
  transition: left 0.3s ease-in-out;
  background: #eef0fc;
  overflow: hidden;
}
.content {
  width: auto;
  height: 100%;
  padding: 20px;
  overflow-y: scroll;
  box-sizing: border-box;
  background-color: white;
}
.content::-webkit-scrollbar {
  width: 0;
}

.content-collapse {
  left: 65px;
}
</style>
