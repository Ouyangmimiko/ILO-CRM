import { defineStore } from "pinia";

interface ListItem {
  name: string;
  path: string;
  title: string;
}

export const useTabsStore = defineStore("tabs", {
  state: () => {
    return {
      list: <ListItem[]>[],
    };
  },
  getters: {
    show: (state) => {
      return state.list.length > 0;
    },
    nameList: (state) => {
      return state.list.map((item) => item.name);
    },
  },
  actions: {
    delTabsItem(index: number) {
      console.log('Deleting item at index:', index);
      this.list.splice(index, 1);
    },
    setTabsItem(data: ListItem) {
      console.log('Setting tab item:', data);
      this.list.push(data);
    },
    clearTabs() {
      console.log('Clearing all tabs');
      this.list = [];
    },
    closeTabsOther(data: ListItem[]) {
      console.log('Closing other tabs:', data);
      this.list = data;
    },
    closeCurrentTab(data: any) {
      for (let i = 0, len = this.list.length; i < len; i++) {
        console.log('Closing current tag:', data.$route.fullPath);
        const item = this.list[i];
        if (item.path === data.$route.fullPath) {
          if (i < len - 1) {
            data.$router.push(this.list[i + 1].path);
          } else if (i > 0) {
            data.$router.push(this.list[i - 1].path);
          } else {
            data.$router.push("/");
          }

          this.list.splice(i, 1);
          break;
        }
      }
    },
  },
});
