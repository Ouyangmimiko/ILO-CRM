import { defineStore } from "pinia";

interface ObjectList {
  [key: string]: string[];
}

export const usePermissStore = defineStore("permiss", {
  state: () => {
    const defaultList: ObjectList = {
      master: [
        "system-user",
        "system-role",
        "database-management",
        "mentor-management",
        "YiI-management",
        "projects-management",
        "setting",
      ],
      admin: [
        "system-user",
        "system-role",
        "database-management",
        "mentor-management",
        "YiI-management",
        "projects-management",
        "setting",
      ],
      user: [
        "database-management",
        "mentor-management",
        "YiI-management",
        "projects-management",
        "setting",
      ],
    } as ObjectList;
    const name = localStorage.getItem("ILO_user_name");
    const role = localStorage.getItem("User_role");
    if (name === "admin" && (role === "admin" || role === "master")) {
      localStorage.setItem("User_role", "master");
      return {
        key: defaultList.master as string[],
        defaultList,
      }
    }
    return {
      key: (role === "admin"? defaultList.admin
          : defaultList.user) as string[],
      defaultList,
    }
  },
  actions: {
    handleSet(val: string[]) {
      this.key = val;
    },
  },
});
