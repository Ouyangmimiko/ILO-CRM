import { RouteRecordRaw, createRouter, createWebHashHistory } from "vue-router";
import Home from "../views/home.vue";
import NProgress from "nprogress";
import "nprogress/nprogress.css";
import { usePermissStore } from "../store/permiss";

const routes: RouteRecordRaw[] = [
  {
    path: "/",
    name: "Home",
    component: Home,
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
  history: createWebHashHistory(),
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
