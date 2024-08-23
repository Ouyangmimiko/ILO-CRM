import { Menus } from "../types/menu";

export const menuData: Menus[] = [
  {
    id: "0",
    title: "Welcome",
    index: "/welcome",
    icon: "HomeFilled",
    permissions: ["user", "admin", "noAuth", "master"],
  },
  {
    id: "1",
    title: "Engagement Management",
    index: "1",
    icon: "Grid",
    permissions: ["user", "admin", "master"],
    children: [
      {
        id: "11",
        pid: "1",
        index: "/database-management",
        title: "Master Database",
        permissions: ["user", "admin", "master"],
      },
      {
        id: "12",
        pid: "1",
        index: "/mentor-management",
        title: "Mentoring Status",
        permissions: ["user", "admin", "master"],
      },
      {
        id: "13",
        pid: "1",
        index: "/YiI-management",
        title: "Year in Industry",
        permissions: ["user", "admin", "master"],
      },
      {
        id: "14",
        pid: "1",
        index: "/projects-management",
        title: "Projects Client Status",
        permissions: ["user", "admin", "master"],
      },
    ],
  },
  {
    id: "2",
    title: "User Management",
    index: "2",
    icon: "User",
    permissions: ["master"],
    children: [
      {
        id: "21",
        pid: "2",
        index: "/system-user",
        title: "User Management",
        permissions: ["master"],
      },
      {
        id: "22",
        pid: "2",
        index: "/system-role",
        title: "Role Management",
        permissions: ["master"],
      },
    ],
  },
];
