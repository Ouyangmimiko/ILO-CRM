import {RouteRecordRaw, createRouter, createWebHistory} from "vue-router";
import Home from "../views/home.vue";
import NProgress from "nprogress";
import "nprogress/nprogress.css";
import { usePermissStore } from "../store/permiss";

const routes: RouteRecordRaw[] = [
  {
    path: "/",
    redirect: "/welcome",
  },
  {
    path: "/",
    name: "Home",
    component: Home,
    children: [
      {
        path: "/welcome",
        name: "welcome",
        meta: {
          title: "Welcome",
          noAuth: true,
        },
        component: () =>
          import(/* webpackChunkName: "dashboard" */ "../views/welcome.vue"),
      },
      {
        path: "/system-user",
        name: "system-user",
        meta: {
          title: "User Management",
          permiss: "system-user",
        },
        component: () =>
          import(
            /* webpackChunkName: "system-user" */ "../views/system/userManager.vue"
          ),
      },
      {
        path: "/system-role",
        name: "system-role",
        meta: {
          title: "Role Management",
          permiss: "system-role",
        },
        component: () =>
          import(
            /* webpackChunkName: "system-user" */ "../views/system/userRoleManager.vue"
          ),
      },
      {
        path: "/database-management",
        name: "database-management",
        meta: {
          title: "Master Database",
          permiss: "database-management",
        },
        component: () =>
          import(
            /* webpackChunkName: "system-user" */ "../views/engagement/database.vue"
          ),
      },
      {
        path: "/mentor-management",
        name: "mentor-management",
        meta: {
          title: "Mentoring",
          permiss: "mentor-management",
        },
        component: () =>
          import(
            /* webpackChunkName: "system-user" */ "../views/engagement/mentor.vue"
          ),
      },
      {
        path: "/YiI-management",
        name: "YiI-management",
        meta: {
          title: "YiI",
          permiss: "YiI-management",
        },
        component: () =>
          import(
            /* webpackChunkName: "system-user" */ "../views/engagement/YiI.vue"
          ),
      },
      {
        path: "/projects-management",
        name: "projects-management",
        meta: {
          title: "Projects",
          permiss: "projects-management",
        },
        component: () =>
          import(
            /* webpackChunkName: "system-user" */ "../views/engagement/projects.vue"
          ),
      },
      {
        path: "/setting",
        name: "setting",
        meta: {
          title: "Setting",
          permiss: "setting",
        },
        component: () =>
            import(
                "../views/system/setting.vue"
                )
      },
      {
        path: "/user-center",
        name: "user-center",
        meta: {
          title: "User Center",
          permiss: "user-center"
        },
        component: () =>
            import(
                "../views/system/userCenter.vue"
                )
      }
    ],
  },
  {
    path: "/login",
    meta: {
      title: "Log In",
      noAuth: true,
    },
    component: () =>
      import(/* webpackChunkName: "login" */ "../views/pages/login.vue"),
  },
  {
    path: "/register",
    meta: {
      title: "Register",
      noAuth: true,
    },
    component: () =>
      import(/* webpackChunkName: "register" */ "../views/pages/register.vue"),
  },
  {
    path: "/403",
    meta: {
      title: "No Permission",
      noAuth: true,
    },
    component: () =>
      import(/* webpackChunkName: "403" */ "../views/pages/403.vue"),
  },

  {
    path: "/404",
    meta: {
      title: "Can't find the page",
      noAuth: true,
    },
    component: () =>
      import(/* webpackChunkName: "404" */ "../views/pages/404.vue"),
  },

  { path: "/:path(.*)", redirect: "/404" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, _, next) => {
  NProgress.start();
  const role = localStorage.getItem("ILO_user_name");
  const permiss = usePermissStore();
  if (!role && !to.meta.noAuth) {
    next("/login");
  } else if (
    typeof to.meta.permiss === "string" &&
    !permiss.key.includes(to.meta.permiss)
  ) {
    // if no permission, enter 403
    next("/403");
  } else {
    next();
  }
});

router.afterEach(() => {
  NProgress.done();
});

export default router;
