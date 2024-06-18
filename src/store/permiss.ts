import { defineStore } from "pinia";

interface ObjectList {
  [key: string]: string[];
}

export const usePermissStore = defineStore("permiss", {
  state: () => {
    const defaultList: ObjectList = {
      admin: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10"],
      user: ["0", "1", "2"],
    };

    const username = localStorage.getItem("ILO_user_name");
    return {
      key: (username === "admin"
        ? defaultList.admin
        : defaultList.user) as string[],
      defaultList,
    };
  },
  actions: {
    handleSet(val: string[]) {
      this.key = val;
    },
  },
});
