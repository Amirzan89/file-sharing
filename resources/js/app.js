import './bootstrap';
// import { createApp } from 'vue'
import { createApp } from 'vue/dist/vue.esm-bundler.js'
import progressComponent  from './Components/uploadForm.vue'
const upload1App = createApp(progressComponent ).mount('#upload1')