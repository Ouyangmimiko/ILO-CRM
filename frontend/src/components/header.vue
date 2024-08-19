<template>
  <div class="header">
    <!-- Fold Button -->
    <div class="header-left">
      <img class="logo" src="../assets/ILO.png" alt="" />
      <div class="web-title">ILO CRM</div>
      <div class="collapse-btn" @click="collapseChange">
        <el-icon v-if="sidebar.collapse">
          <Expand />
        </el-icon>
        <el-icon v-else>
          <Fold />
        </el-icon>
      </div>
    </div>
    <div class="header-right">
      <div class="header-user-con">
        <div class="btn-icon" @click="router.push('/setting')">
          <el-tooltip effect="light" content="App Setting" placement="bottom">
            <el-icon><Setting /></el-icon>
            <i class="el-icon-lx-skin"></i>
          </el-tooltip>
        </div>
        <!--Have logged in-->
        <template v-if="isLoggedIn">
          <!-- User Avatar -->
<!--          <el-avatar class="user-avator" :size="30" :src="imgurl" />-->
          <!-- User Info -->
          <el-dropdown class="user-name" trigger="click" @command="handleCommand">
          <span class="el-dropdown-link">
            {{ username }}
            <el-icon class="el-icon--right">
              <arrow-down />
            </el-icon>
          </span>
            <template #dropdown>
              <el-dropdown-menu>
                <el-dropdown-item command="user">User Center</el-dropdown-item>
                <el-dropdown-item divided command="logout">
                  Log out
                </el-dropdown-item>
              </el-dropdown-menu>
            </template>
          </el-dropdown>
        </template>
        <!--Not logged in-->
        <template v-else>
          <!-- Log In Button -->
          <el-button type="primary" @click="handleCommand('login')">
            Log In
          </el-button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed } from "vue";
import { useSidebarStore } from "../store/siderbar";
import { useRouter } from "vue-router";
import { Expand, Fold } from "@element-plus/icons-vue";
import axios from "../api/axios";

const isLoggedIn = computed(() => {
  return !!localStorage.getItem("ILO_user");
});

const username: string | null = localStorage.getItem("ILO_user_name");

const sidebar = useSidebarStore();

const collapseChange = () => {
  sidebar.handleCollapse();
};

onMounted(() => {
  if (document.body.clientWidth < 1500) {
    collapseChange();
  }
});

const router = useRouter();
const handleCommand = async (command: string) => {
  if (command == "logout") {
    try {
      await axios.get('/api/logout');
    } catch (error) {
      console.error('Logout error:', error);
    }
    localStorage.removeItem("ILO_user_name");
    localStorage.removeItem("auth_token");
    localStorage.removeItem("ILO_user");
    localStorage.removeItem("User_role");
    router.push("/login");
  } else if (command === "user") {
    router.push("/user-center");
  } else if (command === "login") {
    router.push("/login");
  }
};
</script>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-sizing: border-box;
  width: 100%;
  height: 70px;
  color: var(--header-text-color);
  background-color: var(--header-bg-color);
  /* border-bottom: 1px solid #ddd; */
}

.header-left {
  display: flex;
  align-items: center;
  padding-left: 20px;
  height: 100%;
}

.logo {
  width: 35px;
}

.web-title {
  margin: 0 40px 0 10px;
  font-size: 22px;
}

.collapse-btn {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  padding: 0 10px;
  cursor: pointer;
  opacity: 0.8;
  font-size: 22px;
}

.collapse-btn:hover {
  opacity: 1;
}

.header-right {
  float: right;
  padding-right: 50px;
}

.header-user-con {
  display: flex;
  height: 70px;
  align-items: center;
}

.btn-fullscreen {
  transform: rotate(45deg);
  margin-right: 5px;
  font-size: 24px;
}

.btn-icon {
  position: relative;
  width: 30px;
  height: 30px;
  text-align: center;
  cursor: pointer;
  display: flex;
  align-items: center;
  color: var(--header-text-color);
  margin: 0 5px;
  font-size: 20px;
}

.btn-bell-badge {
  position: absolute;
  right: 4px;
  top: 0px;
  width: 8px;
  height: 8px;
  border-radius: 4px;
  background: #f56c6c;
  color: var(--header-text-color);
}

.user-avator {
  margin: 0 10px 0 20px;
}

.el-dropdown-link {
  color: var(--header-text-color);
  cursor: pointer;
  display: flex;
  align-items: center;
}

.el-dropdown-menu__item {
  text-align: center;
}
</style>
