import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Import Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css';
// Import tvoj glavni SCSS fajl
import './assets/main.scss';

createApp(App)
  .use(router)
  .mount('#app');
