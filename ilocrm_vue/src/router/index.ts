import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import store from '@/store'

import AboutView from '../views/AboutView.vue'
import HomeView from '../views/HomeView.vue'

import SignUpView from '../views/SignUpView.vue'
import LoginView from '../views/LoginView.vue'
import DashboardView from '../views/dashboard/DashboardView.vue'
import MyAccountView from '../views/dashboard/MyAccountView.vue'
import MasterDBView from '@/views/database/MasterDBView.vue'
import MentoringView from '@/views/database/MentoringView.vue'
import YiIView from '@/views/database/YiIView.vue'
import ProjectsView from '@/views/database/ProjectsView.vue'
import ModifyView from '@/views/management/ModifyView.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    component: AboutView
  },
  {
    path: '/sign-up',
    name: 'SignUp',
    component: SignUpView
  },
  {
    path: '/log-in',
    name: 'LogIn',
    component: LoginView
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardView,
    meta: {
      requireLogin: true
    }
  },
  {
    path: '/dashboard/my-account',
    name: 'MyAccount',
    component: MyAccountView,
    meta: {
      requireLogin: true
    }
  },
  {
    path: '/master-database',
    name: 'MasterDB',
    component: MasterDBView,
    meta: {
      requireLogin: true
    }
  },
  {
    path: '/mentoring',
    name: 'Mentoring',
    component: MentoringView,
    meta: {
      requireLogin: true
    }
  },
  {
    path: '/year-in-industry',
    name: 'YiI',
    component: YiIView,
    meta: {
      requireLogin: true
    }
  },
  {
    path: '/projects',
    name: 'Projects',
    component: ProjectsView,
    meta: {
      requireLogin: true
    }
  },
  {
    path: '/modify',
    name: 'Modify',
    component: ModifyView,
    meta: {
      requireLogin: true
    }
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requireLogin) && !store.state.isAuthenticated) {
    next('/log-in')
  } else {
    next()
  }
})

export default router
