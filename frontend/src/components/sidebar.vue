<template>
  <div class="sidebar">
    <el-menu
      class="sidebar-el-menu"
      :default-active="onRoutes"
      :collapse="sidebar.collapse"
      :background-color="sidebar.bgColor"
      :text-color="sidebar.textColor"
      router
    >
      <template v-for="item in filteredMenuData" :key="item.id">
        <template v-if="item.children">
          <el-sub-menu :index="item.index">
            <template #title>
              <el-icon>
                <component :is="item.icon"></component>
              </el-icon>
              <span>{{ item.title }}</span>
            </template>
            <template v-for="subItem in item.children" :key="subItem.id">
              <el-sub-menu v-if="subItem.children" :index="subItem.index">
                <template #title>{{ subItem.title }}</template>
                <el-menu-item v-for="threeItem in subItem.children" :key="threeItem.id" :index="threeItem.index">
                  {{ threeItem.title }}
                </el-menu-item>
              </el-sub-menu>
              <el-menu-item v-else :index="subItem.index">
                {{ subItem.title }}
              </el-menu-item>
            </template>
          </el-sub-menu>
        </template>
        <template v-else>
          <el-menu-item :index="item.index">
            <el-icon>
              <component :is="item.icon"></component>
            </el-icon>
            <span>{{ item.title }}</span>
          </el-menu-item>
        </template>
      </template>
    </el-menu>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useRoute } from "vue-router";
import { useSidebarStore } from "../store/siderbar";
import { menuData } from "./menu";
import {Menus} from "../types/menu.ts";

const route = useRoute();
const onRoutes = computed(() => {
  return route.path;
});

const sidebar = useSidebarStore();
let userRole = localStorage.getItem("User_role");
// Menus the current user have permissions to use
const filteredMenuData = computed(() => {
  function filterMenu(menu: Menus[]): Menus[] {
    return menu.filter((item: Menus): boolean => {
      if (!userRole) {
        userRole = "noAuth";
      }
      if (item.permissions) {
        return item.permissions.includes(userRole);
      } else if (!item.permissions) {
        return true;
      }
      if (item.children) {
        item.children = filterMenu(item.children);
        return item.children.length > 0;
      }
      return true;
    });
  }
  return filterMenu(menuData);
});
</script>

<style scoped>
.sidebar {
  display: block;
  position: absolute;
  left: 0;
  top: 70px;
  bottom: 0;
  overflow-y: scroll;
}

.sidebar::-webkit-scrollbar {
  width: 0;
}

.sidebar-el-menu:not(.el-menu--collapse) {
  width: 255px;
}

.sidebar-el-menu {
  min-height: 100%;
}
</style>
