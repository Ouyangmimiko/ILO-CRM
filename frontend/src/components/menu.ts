import { Menus } from "../types/menu";

export const menuData: Menus[] = [
  {
    id: "0",
    title: "Welcome",
    index: "/welcome",
    icon: "Odometer",
    permissions: ["user", "admin", "noAuth"],
  },
  {
    id: "1",
    title: "Engagement Management",
    index: "1",
    icon: "HomeFilled",
    permissions: ["user", "admin"],
    children: [
      {
        id: "11",
        pid: "1",
        index: "/database-management",
        title: "Master Database",
        permissions: ["user", "admin"],
      },
      {
        id: "12",
        pid: "1",
        index: "/mentor-management",
        title: "Mentoring",
        permissions: ["user", "admin"],
      },
      {
        id: "13",
        pid: "1",
        index: "/YiI-management",
        title: "YiI",
        permissions: ["user", "admin"],
      },
      {
        id: "14",
        pid: "1",
        index: "/projects-management",
        title: "Projects",
        permissions: ["user", "admin"],
      },
    ],
  },
  {
    id: "2",
    title: "User Management",
    index: "2",
    icon: "Calendar",
    permissions: ["admin"],
    children: [
      {
        id: "21",
        pid: "2",
        index: "/system-user",
        title: "User Management",
        permissions: ["admin"],
      },
      {
        id: "22",
        pid: "2",
        index: "/system-role",
        title: "Role Management",
        permissions: ["admin"],
      },
    ],
  },
];
