<template>
  <div class="tabs-container">
    <el-tabs
      v-model="activePath"
      class="tabs"
      type="card"
      closable
      @tab-click="clickTabs"
      @tab-remove="closeTabs"
    >
      <el-tab-pane
        v-for="item in tabs.list"
        :key="item.path"
        :label="item.title"
        :name="item.path"
        @click="setTags(item)"
      ></el-tab-pane>
    </el-tabs>
    <div class="tabs-close-box">
      <el-dropdown @command="handleTags">
        <el-button size="small" type="primary" plain>
          Tags Options
          <el-icon class="el-icon--right">
            <arrow-down />
          </el-icon>
        </el-button>
        <template #dropdown>
          <el-dropdown-menu size="small">
            <el-dropdown-item command="other">Close Other</el-dropdown-item>
            <el-dropdown-item command="current">Close Current</el-dropdown-item>
            <el-dropdown-item command="all">Close All</el-dropdown-item>
          </el-dropdown-menu>
        </template>
      </el-dropdown>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { onBeforeRouteUpdate, useRoute, useRouter } from "vue-router";
import { useTabsStore } from "../store/tabs";

const route = useRoute();
const router = useRouter();
const activePath = ref(route.fullPath);
const tabs = useTabsStore();

// Set Tags
const setTags = (route: any) => {
  const isExist = tabs.list.some((item) => {
    return item.path === route.fullPath;
  });
  if (!isExist) {
    tabs.setTabsItem({
      name: route.name,
      title: route.meta.title,
      path: route.fullPath,
    });
  }
};
setTags(route);
onBeforeRouteUpdate((to) => {
  setTags(to);
});

// cloase all tags
const closeAll = () => {
  tabs.clearTabs();
  router.push("/");
};
const closeOther = () => {
  const curItem = tabs.list.filter((item) => {
    return item.path === route.fullPath;
  });
  tabs.closeTabsOther(curItem);
};

const handleTags = (command: string) => {
  switch (command) {
    case "current":
      tabs.closeCurrentTag({
        $router: router,
        $route: route,
      });
      break;
    case "all":
      closeAll();
      break;
    case "other":
      closeOther();
      break;
  }
};

const clickTabs = (item: any) => {
  router.push(item.props.name);
};
const closeTabs = (path: string) => {
  const index = tabs.list.findIndex((item) => item.path === path);
  tabs.delTabsItem(index);
  const item = tabs.list[index] || tabs.list[index - 1];
  router.push(item ? item.path : "/");
};

watch(
  () => route.fullPath,
  (newVal, _oldVal) => {
    activePath.value = newVal;
  }
);
</script>

<style scoped>
.tabs-container {
  position: relative;
  overflow: hidden;
  background: #fff;
  padding: 0px 120px 0 0;
}
.el-tabs__header {
  margin-bottom: 0;
}

.el-tabs__nav-next,
.el-tabs__nav-prev {
  line-height: 32px;
}

.tabs-close-box {
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  box-sizing: border-box;
  padding-top: 1px;
  text-align: center;
  width: 110px;
  height: 24px;
  background: #fff;
  box-shadow: -3px 0 15px 3px rgba(0, 0, 0, 0.1);
  z-index: 10;
}
</style>
