import { createApp } from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import axios from 'axios'
import { worker } from './mocks/browser';// configure for mocking back-end

// Start the mock service worker
worker.start();

axios.defaults.baseURL = 'http://127.0.0.1:8000'

const app = createApp(App);
app.use(store);
app.use(router);
// 将 axios 添加到 Vue 实例的全局属性中
app.config.globalProperties.$axios = axios;

// 挂载 Vue 应用程序到 HTML 元素上
app.mount('#app');
