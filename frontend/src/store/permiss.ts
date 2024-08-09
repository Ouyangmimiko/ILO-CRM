import { defineStore } from "pinia";

interface ObjectList {
  [key: string]: string[];
}

export const usePermissStore = defineStore("permiss", {
  state: () => {
    const defaultList: ObjectList = {
      admin: [
        "system-user",
        "system-role",
        "database-management",
        "mentor-management",
        "YiI-management",
        "projects-management",
      ],
      user: [
        "database-management",
        "mentor-management",
        "YiI-management",
        "projects-management",
      ],
    } as ObjectList;
    const role = localStorage.getItem("User_role");
    return {
      key: (role === "admin"? defaultList.admin
          : defaultList.user) as string[],
      defaultList,
    }
    // const username = localStorage.getItem("ILO_user_name");
    // return {
    //   key: (username === "admin"
    //     ? defaultList.admin
    //     : defaultList.user) as string[],
    //   defaultList,
    // };
  },
  actions: {
    handleSet(val: string[]) {
      this.key = val;
    },
  },
});
