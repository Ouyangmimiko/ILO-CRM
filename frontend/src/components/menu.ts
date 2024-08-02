import { Menus } from "../types/menu";

export const menuData: Menus[] = [
  {
    id: "0",
    title: "Welcome",
    index: "/welcome",
    icon: "Odometer",
  },
  {
    id: "1",
    title: "Engagement Management",
    index: "1",
    icon: "HomeFilled",
    children: [
      {
        id: "11",
        pid: "1",
        index: "/database-management",
        title: "Master Database",
      },
      {
        id: "12",
        pid: "1",
        index: "/mentor-management",
        title: "Mentoring",
      },
      {
        id: "13",
        pid: "1",
        index: "/YiI-management",
        title: "YiI",
      },
      {
        id: "14",
        pid: "1",
        index: "/projects-management",
        title: "Projects",
      },
    ],
  },
  {
    id: "2",
    title: "User Management",
    index: "2",
    icon: "Calendar",
    children: [
      {
        id: "21",
        pid: "2",
        index: "/system-user",
        title: "User Management",
      },
      {
        id: "22",
        pid: "2",
        index: "/system-role",
        title: "Role Management",
      },
    ],
  },
];
