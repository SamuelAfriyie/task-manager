
import '../css/app.css'
import { createApp } from 'vue'
import router from './router/router';
import AppLayout from './Layouts/AppLayout.vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

const app = createApp(AppLayout);
app.use(router);
app.use(pinia);
app.mount('#app');