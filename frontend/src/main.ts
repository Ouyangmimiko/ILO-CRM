import { createApp } from "vue";
import { createPinia } from "pinia";
import * as ElementPlusIconsVue from "@element-plus/icons-vue";

import App from "./App.vue";
import "element-plus/dist/index.css";
import "./assets/css/icon.css";

import router from "./router";

const pinia = createPinia();

function iniLocalStorage() {
  if (!localStorage.getItem('initialised')) {
    localStorage.setItem('lastYearRange', '10');
    localStorage.setItem('betweenRange', '3')
    localStorage.setItem('initialised', 'true');
  }
}
iniLocalStorage();

const app = createApp(App);
app.use(pinia);
app.use(router);

// register elementplus icons
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
  app.component(key, component);
}

app.mount("#app");
