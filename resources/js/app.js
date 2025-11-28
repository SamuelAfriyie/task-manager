
import '../css/app.css'
import { createApp } from 'vue'
import router from './router/router';
import AppLayout from './Layouts/AppLayout.vue';


const app = createApp(AppLayout);
app.use(router);
app.mount('#app');